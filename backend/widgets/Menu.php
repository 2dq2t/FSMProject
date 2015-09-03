<?php
namespace backend\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

class Menu extends \yii\base\Widget
{
    public $items = [];
    /**
     * @var array list of HTML attributes shared by all menu [[items]]. If any individual menu item
     * specifies its `options`, it will be merged with this property before being used to generate the HTML
     * attributes for the menu item tag. The following special options are recognized:
     *
     * - tag: string, defaults to "li", the tag name of the item container tags.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $itemOptions = [];
    /**
     * @var string the template used to render the body of a menu which is a link.
     * In this template, the token `{url}` will be replaced with the corresponding link URL;
     * while `{label}` will be replaced with the link text.
     * This property will be overridden by the `template` option set in individual menu items via [[items]].
     */
    public $linkTemplate = '<a href="{url}">
                                <i class="{icon}"></i>
                                <span class="title">{label}</span>
                            </a>';

    public $linkParentTemplate = '<a href="{url}">
                                <i class="{icon}"></i>
                                <span class="title">{label}</span>
                                <span class="arrow {open}"></span>
                            </a>';
    /**
     * @var string the template used to render the body of a menu which is NOT a link.
     * In this template, the token `{label}` will be replaced with the label of the menu item.
     * This property will be overridden by the `template` option set in individual menu items via [[items]].
     */
    public $labelTemplate = '{label}';
    /**
     * @var string the template used to render a list of sub-menus.
     * In this template, the token `{items}` will be replaced with the rendered sub-menu items.
     */
    public $submenuTemplate = "\n<ul class='sub-menu'>\n{items}\n</ul>\n";
    /**
     * @var boolean whether the labels for menu items should be HTML-encoded.
     */
    public $encodeLabels = true;
    /**
     * @var string the CSS class to be appended to the active menu item.
     */
    public $activeCssClass = 'active';
    public $openCssClass = 'open';
    /**
     * @var boolean whether to automatically activate items according to whether their route setting
     * matches the currently requested route.
     * @see isItemActive()
     */
    public $activateItems = true;
    /**
     * @var boolean whether to activate parent menu items when one of the corresponding child menu items is active.
     * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
     */
    public $activateParents = true;
    /**
     * @var boolean whether to hide empty menu items. An empty menu item is one whose `url` option is not
     * set and which has no visible child menu items.
     */
    public $hideEmptyItems = true;
    /**
     * @var array the HTML attributes for the menu's container tag. The following special options are recognized:
     *
     * - tag: string, defaults to "ul", the tag name of the item container tags. Set to false to disable container tag.
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var string the CSS class that will be assigned to the first item in the main menu or each submenu.
     * Defaults to null, meaning no such CSS class will be assigned.
     */
    public $firstItemCssClass;
    /**
     * @var string the CSS class that will be assigned to the last item in the main menu or each submenu.
     * Defaults to null, meaning no such CSS class will be assigned.
     */
    public $lastItemCssClass;
    /**
     * @var string the route used to determine if a menu item is active or not.
     * If not set, it will use the route of the current request.
     * @see params
     * @see isItemActive()
     */
    public $route;
    /**
     * @var array the parameters used to determine if a menu item is active or not.
     * If not set, it will use `$_GET`.
     * @see route
     * @see isItemActive()
     */
    public $params;


    /**
     * Renders the menu.
     */
    public function run()
    {
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }

        $items = $this->normalizeItems($this->items, $hasActiveChild);
        if (!empty($items)) {
            echo $this->renderItems($items);
        }
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (isset($items['open'])) {
                $class[] = $this->openCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $menu .= strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }

            if ($menu == '') continue;
            $menu = str_replace("<li></li>\n", '', $menu);
            if (preg_match("/\n<ul class='sub-menu'>\n\n<\/ul>\n/", $menu)) continue;
            if ($tag === false) {
                $lines[] = $menu;
            } else {
                $lines[] = Html::tag($tag, $menu, $options);
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     */
    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            if (!$this->checkPermission($item['url'])) {return '';}
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{url}' => Html::encode(Url::toRoute($item['url'][0])),
                '{label}' => $item['label'],
                '{icon}' => $item['icon'],
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->linkParentTemplate);

            return strtr($template, [
                '{url}' => 'javascript:;',
                '{label}' => $item['label'],
                '{icon}' => $item['icon'],
                '{open}' => isset($item['open']) ? 'open' : ''
            ]);
        }
    }

    protected function checkPermission($url) {
        $urls = explode('/', $url[0]);
        if ($urls[0] == 'site') return true;
        if (Yii::$app->user->can($url[0])) {
            return true;
        } else if (Yii::$app->user->can('/' . $urls[0] . '/*')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Normalizes the [[items]] property to remove invisible items and activate certain items.
     * @param array $items the items to be normalized.
     * @param boolean $active whether there is an active child menu item.
     * @return array the normalized menu items
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $items[$i]['open'] = true;
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }

        return array_values($items);
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url'])) {
            $count = count($item['url']);
            $i = 0;
            foreach ($item['url'] as $i => $values) {
                $i++;
                $route = $values;
                if ($route !== '/' && Yii::$app->controller) {
                    $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
                }
                if (ltrim($route, '/') !== $this->route) {
                    if ($count != $i) continue;
                    return false;
                }
                unset($item['url']['#']);
                if (count($values) > 1) {
                    foreach (array_splice($values, 1) as $name => $value) {
                        if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                            return false;
                        }
                    }
                }
                return true;
            }
        }

        return false;
    }
}