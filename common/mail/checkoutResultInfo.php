<?php
/**
 * Created by PhpStorm.
 * User: TuanThinh
 * Date: 20/08/2015
 * Time: 2:26 CH
 */
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
    <META HTTP-EQUIV="CONTENT-TYPE" CONTENT="text/html; charset=utf-8">
    <TITLE></TITLE>
    <META NAME="GENERATOR" CONTENT="LibreOffice 4.1.6.2 (Linux)">
    <META NAME="CREATED" CONTENT="20150820;81200000000000">
    <META NAME="CHANGEDBY" CONTENT="Tuấn Thính">
    <META NAME="CHANGED" CONTENT="20150820;82400000000000">
    <META NAME="AppVersion" CONTENT="15.0000">
    <META NAME="DocSecurity" CONTENT="0">
    <META NAME="HyperlinksChanged" CONTENT="false">
    <META NAME="LinksUpToDate" CONTENT="false">
    <META NAME="ScaleCrop" CONTENT="false">
    <META NAME="ShareDoc" CONTENT="false">
    <STYLE TYPE="text/css">
        <!--
        @page { margin-left: 0.46in; margin-right: 0.74in; margin-top: 0.44in; margin-bottom: 0.19in }
        P { margin-bottom: 0.08in; direction: ltr; widows: 2; orphans: 2 }
        -->
    </STYLE>
</HEAD>
<BODY LANG="en-US" DIR="LTR">
<P ALIGN="CENTER" STYLE="margin-left: 0.8in; margin-top: 0.04in; margin-bottom: 0in">
    <SUP><FONT FACE="Arial, serif"><FONT SIZE=5><B>Fresh Garden</B></FONT></FONT></SUP></P>
<P ALIGN="CENTER" STYLE="margin-left: 0.11in; margin-top: 0.02in; margin-bottom: 0in">
    <FONT FACE="Arial, serif"><B>Địa chỉ: Khu Công Nghệ Cao Hòa Lạc</B></FONT>
</P>
<P ALIGN="CENTER" STYLE="margin-left: 0.11in; margin-top: 0.02in; margin-bottom: 0in">
    <FONT FACE="Arial, serif"><B>Tel: 043-987-5643</B></FONT></P>
<P STYLE="margin-top: 0in; margin-bottom: 0in; line-height: 0.11in"><BR>
</P>
<P STYLE="margin-left: 0.11in; margin-right: 0.56in; margin-bottom: 0in; line-height: 0.26in">
    <FONT FACE="Arial, serif">Tên khách hàng</FONT><FONT FACE="Arial, serif">:</FONT><FONT FACE="Arial, serif">
    </FONT><FONT FACE="Arial, serif">    <?=$customer_info['full_name']?></FONT></P>
<P STYLE="margin-left: 0.11in; margin-right: 0.56in; margin-bottom: 0in; line-height: 0.26in">
    <FONT FACE="Arial, serif">Email</FONT><FONT FACE="Arial, serif">:</FONT><FONT FACE="Arial, serif">
    </FONT><FONT FACE="Arial, serif">    <?=$customer_info['email']?></FONT></P>
<P STYLE="margin-left: 0.11in; margin-right: 0.56in; margin-bottom: 0in; line-height: 0.26in">
    <FONT FACE="Arial, serif">Số Điện Thoại</FONT><FONT FACE="Arial, serif">:</FONT><FONT FACE="Arial, serif">
    </FONT><FONT FACE="Arial, serif">    <?=$customer_info['phone_number']?></FONT></P>
<P STYLE="margin-left: 0.11in; margin-right: 0.56in; margin-bottom: 0in; line-height: 0.26in">
    <FONT FACE="Arial, serif">Địa Chỉ Nhận Hàng</FONT><FONT FACE="Arial, serif">:</FONT><FONT FACE="Arial, serif">
    </FONT><FONT FACE="Arial, serif">    <?=$address['detail']?> - <?=$district['name']?> - <?=$city['name']?></FONT></P>
<P STYLE="margin-left: 0.11in; margin-right: 0.56in; margin-bottom: 0in; line-height: 0.26in">
    <FONT FACE="Arial, serif">Ngày Nhận</FONT><FONT FACE="Arial, serif">:</FONT><FONT FACE="Arial, serif">
    </FONT><FONT FACE="Arial, serif">    <?=date('d/m/Y',$order['receiving_date'])?></FONT></P>
<P STYLE="margin-top: 0.01in; margin-bottom: 0in; line-height: 0.19in">
    <BR>
</P>
<TABLE WIDTH=800 CELLPADDING=0 CELLSPACING=0>
    <COL WIDTH=40>
    <COL WIDTH=228>
    <COL WIDTH=117>
    <COL WIDTH=136>
    <COL WIDTH=140>
    <TR VALIGN=TOP>
        <TD WIDTH=40 HEIGHT=22 STYLE="border: 1.00pt solid #000001; padding: 0in">
            <P STYLE="margin-left: 0.13in; margin-top: 0.04in"><FONT FACE="Arial, serif"><B>TT</B></FONT></P>
        </TD>
        <TD WIDTH=228 STYLE="border: 1.00pt solid #000001; padding: 0in">
            <P ALIGN=CENTER STYLE="margin-left: 0.2in; margin-right: 0.2in; margin-top: 0.04in">
                <FONT FACE="Arial, serif"><B>TÊN HÀNG</B></FONT></P>
        </TD>
        <TD WIDTH=117 STYLE="border: 1.00pt solid #000001; padding: 0in">
            <P STYLE="margin-left: 0.23in; margin-top: 0.04in"><FONT FACE="Arial, serif"><B>SỐ LƯỢNG</B></FONT></P>
        </TD>
        <TD WIDTH=136 STYLE="border: 1.00pt solid #000001; padding: 0in">
            <P STYLE="margin-left: 0.41in; margin-top: 0.04in"><FONT FACE="Arial, serif"><B>ĐƠN GIÁ</B></FONT></P>
        </TD>
        <TD WIDTH=140 STYLE="border: 1.00pt solid #000001; padding: 0in">
            <P STYLE="margin-left: 0.31in; margin-top: 0.04in"><FONT FACE="Arial, serif"><B>THÀNH TIỀN</B></FONT></P>
        </TD>
    </TR>
    <?php $i = 0?>
    <?php foreach ($order_detail as $product) { $i++; ?>
    <TR VALIGN=TOP>
        <TD WIDTH=40 HEIGHT=23 STYLE="border-top: 1.00pt solid #000001; border-bottom: 1px solid #000001; border-left: 1.00pt solid #000001; border-right: 1.00pt solid #000001; padding: 0in">
            <P ALIGN=CENTER STYLE="margin-left: 0.15in; margin-right: 0.14in; margin-top: 0.04in">
                <FONT FACE="Arial, serif"><?=$i?></FONT></P>
        </TD>
        <TD WIDTH=228 STYLE="border-top: 1.00pt solid #000001; border-bottom: 1px solid #000001; border-left: 1.00pt solid #000001; border-right: 1.00pt solid #000001; padding: 0in">
            <P ALIGN=CENTER STYLE="margin-right: 0.12in; margin-top: 0.04in"><FONT FACE="Arial, serif"><?= ucwords($product['product_name']) ?></FONT></P>
        </TD>
        <TD WIDTH=117 STYLE="border-top: 1.00pt solid #000001; border-bottom: 1px solid #000001; border-left: 1.00pt solid #000001; border-right: 1.00pt solid #000001; padding: 0in">
            <P ALIGN=CENTER STYLE="margin-right: 0.12in; margin-top: 0.04in"><FONT FACE="Arial, serif"><?= ucwords($product['quantity']) ?></FONT></P>
        </TD>
        <TD WIDTH=136 STYLE="border-top: 1.00pt solid #000001; border-bottom: 1px solid #000001; border-left: 1.00pt solid #000001; border-right: 1.00pt solid #000001; padding: 0in">
            <P ALIGN=CENTER STYLE="margin-right: 0.12in; margin-top: 0.04in"><FONT FACE="Arial, serif"><?php
                    echo number_format($product['sell_price']) . " " . Yii::t('app', 'VNDLabel');
                    ?></FONT></P>
        </TD>
        <TD WIDTH=140 STYLE="border-top: 1.00pt solid #000001; border-bottom: 1px solid #000001; border-left: 1.00pt solid #000001; border-right: 1.00pt solid #000001; padding: 0in">
            <P ALIGN=CENTER STYLE="margin-right: 0.12in; margin-top: 0.04in"><FONT FACE="Arial, serif"><?php
                                           echo number_format($product['total_price']) . " " . Yii::t('app', 'VNDLabel');
                                            ?></FONT></P>
        </TD>
    </TR>
    <?php } ?>
</TABLE>
<P STYLE="margin-top: 0.01in; margin-bottom: 0in; line-height: 0.18in">
    <BR>
</P>
<P STYLE="margin-left: 0.11in; margin-top: 0.02in; margin-bottom: 0in">
    <FONT FACE="Arial, serif"><I>Thành tiền</I></FONT><FONT FACE="Arial, serif"><I>:</I></FONT><FONT FACE="Arial, serif"><I>
        </I></FONT><FONT FACE="Arial, serif">.........................................................................................................<?= number_format($total_price) . " " . Yii::t('app', 'VNDLabel') ?>..............................................................</FONT></P>
<P STYLE="margin-left: 0.11in; margin-top: 0.02in; margin-bottom: 0in">
    <FONT FACE="Arial, serif"><I>Mã Giảm Giá</I></FONT><FONT FACE="Arial, serif"><I>:</I></FONT><FONT FACE="Arial, serif"><I>
        </I></FONT><FONT FACE="Arial, serif">..................................................................................<?php if(!empty($voucher)){ echo $voucher['code']."(".$voucher['discount']."%)";} else echo "Không..................."; ?>............................................................</FONT></P>
<P STYLE="margin-left: 0.11in; margin-top: 0.02in; margin-bottom: 0in">
    <FONT FACE="Arial, serif"><I>Số Tiền Phải Trả</I></FONT><FONT FACE="Arial, serif"><I>:</I></FONT><FONT FACE="Arial, serif"><I>
        </I></FONT><FONT FACE="Arial, serif">..........................................................................................................<?= number_format($total_price - $discount_price ) . " " . Yii::t('app', 'VNDLabel') ?>....................................................</FONT></P>
<P STYLE="margin-top: 0in; margin-bottom: 0in; line-height: 0.07in"><BR>
</P>
<P ALIGN=RIGHT STYLE="margin-right: 0.09in; margin-top: 0.01in; margin-bottom: 0in"><A NAME="_GoBack"></A>

    <FONT FACE="Arial, serif"><FONT SIZE=4><I>Fresh GarDen</I></FONT></FONT></P>
</BODY>
</HTML>