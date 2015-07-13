<?php

namespace backend\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\i18n\PhpMessageSource;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

class I18nController extends \yii\web\Controller
{
    private $aliases;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className()
            ],
        ];
    }

    private function getAliases() {
        if ($this->aliases === null) {
            $this->aliases = [];

            $count = 0;
            foreach (\Yii::$app->i18n->translations as $name => $translation) {

                $count++;
                if (is_array($translation)) {
                    $translation = \Yii::createObject($translation);
                }

                if (!($translation instanceof PhpMessageSource) || $name == 'yii' || $name == 'app') {
                    continue;
                }

                $basePath = \Yii::getAlias($translation->basePath);
                $rdi = new \RecursiveDirectoryIterator($basePath, \RecursiveDirectoryIterator::SKIP_DOTS);

//                echo "<pre>";
//                var_dump(\Yii::$app->i18n->translations); return;
//                echo "</pre>";
//
                foreach (new \RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::CHILD_FIRST) as $file) {
                    $fileName = $file->getRealpath();
                    if (pathinfo($fileName, PATHINFO_EXTENSION) == 'php') {
                        $alias = $translation->basePath . substr($fileName, strlen($basePath));
                        $this->aliases[$alias] = $fileName;
                    }
                }
            }
        }

        return $this->aliases;
    }

    public function actionIndex()
    {
        $aliases = $this->getAliases();
        $dataProvider = new ArrayDataProvider(['allModels' => $aliases]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id)
    {
        $aliases = $this->getAliases();
        if (!isset($aliases[$id])) {
            throw new NotFoundHttpException;
        }

        if (!is_writable($aliases[$id])) {
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'warning',
                'duration' => 3000,
                'icon' => 'fa fa-pencil',
                'message' => Yii::t('app', 'File "{file}" is not writable.', ['file' => $aliases[$id]]),
                'title' => Yii::t('app', 'Update message')
            ]);

            return $this->redirect(['index']);
        }

        try {
            $messages = Json::encode(include $aliases[$id]);
        } catch (\Exception $e) {
            $messages = '{}';
            Yii::$app->getSession()->setFlash('success', [
                'type' => 'error',
                'duration' => 0,
                'icon' => 'fa fa-pencil',
                'message' => Yii::t('app', 'Cannot read messages'),
                'title' => Yii::t('app', 'Update message')
            ]);
        }

        if (Yii::$app->request->isPost && !is_null(Yii::$app->request->post('messages'))) {
            Yii::$app->response->cookies->add(
                new Cookie(
                    [
                        'name' => 'sortMessages',
                        'value' => Yii::$app->request->post('ksort') == 1,
                    ]
                )
            );
            $messages = Yii::$app->request->post('messages');
            $data = Json::decode($messages);
            $hasErrors = false;
            foreach ((array) $data as $message => $translation) {
                if (!is_string($translation)) {
                    $hasErrors = true;
                    break;
                }
            }
            if (!$hasErrors) {
                try {
                    if (Yii::$app->request->post('ksort') == 1) {
                        ksort($data, SORT_NATURAL | SORT_FLAG_CASE);
                    }
                    file_put_contents($aliases[$id], "<?php\n\nreturn " . var_export($data, true) . ";\n");
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Messages has been saved'));
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'success',
                        'duration' => 3000,
                        'icon' => 'fa fa-pencil',
                        'message' => Yii::t('app', 'Messages has been saved'),
                        'title' => Yii::t('app', 'Update message')
                    ]);
                    $this->refresh();
//                    Yii::$app->end();

                    return $this->redirect(['index']);

                } catch (\Exception $e) {
                    Yii::$app->getSession()->setFlash('success', [
                        'type' => 'error',
                        'duration' => 0,
                        'icon' => 'fa fa-pencil',
                        'message' => Yii::t('app', 'Cannot save messages'),
                        'title' => Yii::t('app', 'Update message')
                    ]);

                    return $this->render(
                        'update',
                        [
                            'alias' => $id,
                            'file' => $aliases[$id],
                            'messages' => $messages,
                        ]
                    );
                }
            } else {
                Yii::$app->getSession()->setFlash('success', [
                    'type' => 'error',
                    'duration' => 0,
                    'icon' => 'fa fa-pencil',
                    'message' => Yii::t('app', 'Wrong data'),
                    'title' => Yii::t('app', 'Update message')
                ]);

                return $this->render(
                    'update',
                    [
                        'alias' => $id,
                        'file' => $aliases[$id],
                        'messages' => $messages,
                    ]
                );
            }
        }

        return $this->render(
            'update',
            [
                'alias' => $id,
                'file' => $aliases[$id],
                'messages' => $messages,
            ]
        );
    }

}
