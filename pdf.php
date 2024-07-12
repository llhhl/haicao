<?php

require_once('./tcpdf_min/tcpdf.php');



class MYPDF extends TCPDF {



    //Page header

    public function Header() {

        // Logo

		//$this->Image('images/logo.png', 10, 10, 50, '', '', 'http://www.cqhaicao.com', '', false, 140);



        // Set font

        $this->SetFont('stsongstdlight', 'B', 12);

        // Title

        //$this->Cell(0, 12, '海草验房|中国第三方专业验房平台                                     ', 0, false, 'C', 0, '', 0, false, 'M', 'M');

		 $this->MultiCell(455, 5, '<div style="border-bottom: solid 3px #000">海草验房|中国第三方专业验房平台111&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </div>', 0, 'C', 0, 0, 0, 3, true,'',1);

    }



    // Page footer

    public function Footer() {

        // Position at 15 mm from bottom

        $this->SetY(-15);

        // Set font

        //$this->SetFont('helvetica', 'I', 8);

        // Page number

		$this->SetFont('stsongstdlight', 'B', 9);

		$this->MultiCell(180, 10, '<font size="+1">验房咨询热线：400-033-4568&nbsp; &nbsp; www.cqhaicao.com', 0, 'L', 0, 20, 20, 285, true,'',1);

		$this->MultiCell(180, 10, '<font size="+1">投拆电话：17338385505（如您对本次验房不满意，请拨打投诉电话！)', 0, 'L', 0, 20, 20, 290, true,'',1);



       //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

    }

}





// create new PDF document

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);



// set document information





// set default header data

$pdf->SetHeaderData("", "","海草验房|中国第三方专业验房平台                                     ", "");

$pdf->SetFont('stsongstdlight', '', 14, '', true);

// set header and footer fonts

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

$pdf->setFooterFont(Array('', '', PDF_FONT_SIZE_DATA));



// set default monospaced font

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins

$pdf->SetMargins(10, 10, 10);

$pdf->SetHeaderMargin(0,0,0);

$pdf->SetFooterMargin(0,0,0);



// set auto page breaks

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set image scale factor

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)

if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {

    require_once(dirname(__FILE__).'/lang/eng.php');

    $pdf->setLanguageArray($l);

}



// ---------------------------------------------------------



// set font

//$pdf->SetFont('stsongstdlight', '', 11);



// add a page

$pdf->AddPage();



//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')



// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

$pdf->Image('images/logo.png', 30, 15, 40, '', '', 'http://www.cqhaicao.com', '', false, 120);

$pdf->MultiCell(75, 5, '<font size="+10">海草验房服务报告</font>', 0, 'R', 0, 20, 80, 25, true,'',1);

$pdf->MultiCell(125, 25, '<font size="+1">编&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号 ： 22222112111', 0, 'R', 0,20, 80, 45, true,'',1);

$pdf->MultiCell(75, 20, '<font size="+3">房屋验收类型：	 XXXX', 0, 'R', 0, 20, 40, 60, true,'',1);

$pdf->MultiCell(75, 20, '<font size="+3">委   托  方 ：	 XXXX', 0, 'R', 0, 20, 40, 80, true,'',1);

$pdf->MultiCell(75, 20, '<font size="+3">验 房 人 员：	 XXXX', 0, 'R', 0, 20, 40, 100, true,'',1);

$pdf->MultiCell(75, 20, '<font size="+3">验 房 日 期：	 XXXX', 0, 'R', 0, 20, 40, 120, true,'',1);

$pdf->Image('images/z.png', 60, 80, 80, '', '', 'http://www.cqhaicao.com', '', false, 120);

$pdf->MultiCell(180, 20, '<font size="+1">   200000+业主的共同选择   我们只验房不装修', 0, 'R', 0, 20, 0, 150, true,'',1);



$pdf->Image('images/ma.jpg', 40, 160, 120, '', '', 'http://www.cqhaicao.com', '', false, 110);

$pdf->MultiCell(180, 20, '<font size="+1">关于我们', 0, 'L', 0, 20, 20, 240, true,'',1);

$pdf->MultiCell(180, 20, '<font size="+1">海草®验房是海草（重庆）科技有限公司旗下品牌，海草公司是由多家老牌验房公司联合成立，纯粹验房的专业技术公司，结合国家建筑装饰标准制定的毛坯房22步查验项目，精装房39步查验项目。', 0, 'L', 0, 20, 20, 250, true,'',1);

$pdf->AddPage();

/*第二页*/

$pdf->Image('images/sy.png', 20, 40, 140, '', '', 'http://www.cqhaicao.com', '', false, 120);

$pdf->setCellHeightRatio(1.6);

$pdf->MultiCell(600, 20, '<table border="1" cellspacing="0" >

  <tr >

    <td width="100" valign="center" ><p align="center" >委 托 人 </p></td>

    <td width="200" valign="center" ><p align="center" >张三 </p></td>

    <td width="100" valign="center" ><p align="center" >电   话 </p></td>

    <td width="200" valign="center" ><p align="center" >15912405678 </p></td>

  </tr>

  <tr >

    <td valign="center" width="100" ><p align="center" >市/区/县 </p></td>

    <td  valign="center" ><p align="center" >重庆 </p></td>

    <td  valign="center" ><p align="center" >楼   盘 </p></td>

    <td  valign="center" ><p align="center" >远洋九公子 </p></td>

  </tr>

  <tr >

    <td  valign="center" ><p align="center" >房   号 </p></td>

    <td valign="center" ><p align="center" >45-2-6-9 </p></td>

    <td valign="center" ><p align="center" >房屋面积 </p></td>

    <td valign="center" ><p align="center" >108 </p></td>

  </tr>

  <tr >

    <td  valign="top" colspan="4" ><p align="justify" >应业主委托，海草验房派专业验房师对该处房屋当前建筑质量进行查验，查验结果如下：该房屋共检查出  处问题，所有问题均已在室内相关位置标记。 </p></td>

  </tr>

</table>', 0, 'C', 0, 20, 20, 15, true,'',1);



//$pdf->MultiCell(180, 20, '<font size="+1">以下是验房问题的图文描述（人员上传图文，软件生成，本行不显示）', 0, 'L', 0, 20, 20, 80, true,'',1);

$pdf->setCellHeightRatio(1.6);

$html = <<<EOD



<table width="600" border="1" cellspacing="0"  align="center"> 

 <tr >

    <td colspan="2" valign="middle" bgcolor="#008080" align="center">

	<br />

客厅查验0

    </td>

  </tr>

  

  <tr >

    <td colspan="2" valign="top" align="left">

<p>

	1、开关不平<br />



2、对讲倾斜<br />



3、XXX</p>





    </td>

  </tr>

    <tr >

    <td valign="middle"  align="center" height="200"><img src="images/wt.png" width="300" height="200"></td>

    <td valign="middle"  align="center" height="200"><img src="images/wt.png" width="300" height="200"></td>

  </tr>

    <tr >

      <td valign="middle"  align="center" height="200"><img src="images/wt.png" width="300" height="200"></td>

      <td valign="middle" align="center"  height="200"><img src="images/wt.png" width="300" height="200"></td>

    </tr>

	 

</table>

EOD;

//如果要对html指定 宽度  writeHTMLCell更方便

$pdf->setCellHeightRatio(1.4);







 $pdf->SetXY(21,59, $rtloff=false);

$pdf->writeHTML($html, true, false, true, false, 'C');

$pdf->Image('images/sy.png', 20, 40, 140, '', '', 'http://www.cqhaicao.com', '', false, 120);

/*验房信息页*/

/////////////////////////////







$pdf->AddPage();

$html = <<<EOD



<table width="600" border="1" cellspacing="0" align="align"> 

 <tr >

    <td colspan="2" valign="middle" bgcolor="#008080" align="center">

	<br />

客厅查验11

    </td>

  </tr>

  

  <tr >

    <td colspan="2" valign="top" align="left" >

	1、开关不平<br />

2、对讲倾开关不平开关不平开关不平

    </td>

  </tr>

       <tr >

    <td valign="middle"  align="center"><img src="images/wt.png" width="240" height="180"></td>

    <td valign="middle"  align="center"><img src="images/wt.png" width="240" height="180"></td>

  </tr>

    <tr >

      <td valign="middle"  align="center"><img src="images/wt.png"width="240" height="180"></td>

      <td valign="middle" align="center" ><img src="images/wt.png" width="240" height="180"></td>

    </tr>

	 <tr >

    <td colspan="2" valign="middle" bgcolor="#008080" align="center">

	<br />

客厅查验222

    </td>

  </tr>

  

  <tr >

    <td colspan="2" valign="top" align="left" >



	1、开关开关不平开关不平开关不平开关不平开关不平开关不平开关不平开关不平开关不平开关不平开关不平开关不平开关不平不平<br />

2、对讲倾开关不平开关不平开关不平斜  </td>

  </tr>

       <tr >

    <td valign="middle"  align="center"><img src="images/wt.png" width="240" height="180"></td>

    <td valign="middle"  align="center"><img src="images/wt.png" width="240" height="180"></td>

  </tr>

    <tr >

      <td valign="middle"  align="center"><img src="images/wt.png"width="240" height="180"></td>

      <td valign="middle" align="center" ><img src="images/wt.png" width="240" height="180"></td>

    </tr>

</table>

EOD;

$pdf->setCellHeightRatio(1.4);

$pdf->SetXY(21,12, $rtloff=false);

//如果要对html指定 宽度  writeHTMLCell更方便

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Image('images/sy.png', 20, 40, 140, '', '', 'http://www.cqhaicao.com', '', false, 120);









/*第三页*/

$pdf->AddPage();

$pdf->SetFillColor(0, 128, 128);

$pdf->MultiCell(180, 5, '<font size="+5">【毛坯房通用问题整改建议】 </font>', 0, 'L',1, 0, 20, 15, true,'',1);



$pdf->MultiCell(180, 5, '<font>1、金属门门框与墙有缝隙：先填充水泥砂浆外侧再用耐候胶封闭。<br />



2、墙面空鼓：使用切割机切割抹灰层空鼓边缘处，在用凿子凿干净空鼓处，并做基层清理，然后用水养护好，再重新抹灰找平； 

<br />

3、墙面抹灰层开裂：敲掉重新找平 需挂网处理； 

<br />

4、地面抹灰层开裂：表面涂抹环氧胶泥：涂抹环氧胶泥前，先将裂缝附近 80～100mm 宽度范围内的灰尘、浮渣用压缩空气吹净，或用钢丝刷、砂纸、毛刷清除干净并洗净，油污可用二甲苯或丙酮擦洗一遍，如表面潮湿，应用喷灯烘烤干燥、预热，以保证环氧胶泥与混凝土粘结良好。若基层难以干燥，则用环氧煤焦油胶泥涂抹。涂抹时，用毛刷或刮板均匀蘸取胶泥，并涂刮在裂缝表面。

<br />

5、墙面返碱： 需要将墙面返碱的区域的面层完全铲除干净，看到墙体基层为止。最好再使用具有一定浓度的草酸，加入适量的温水进行液体调制，用刷子均匀的涂抹在返碱的墙面上。利用化学上的酸碱中和反应原理，来解决墙面返碱问题。但反应完成后，用水从返碱的墙面上方向下冲洗，待墙面干净后。则需要开窗进行通风，让墙面进行充分的干燥，再来进行墙面的修补工作。

<br />

6、玻璃划痕：一般不能处理，若划痕较多建议更换玻璃。 </font>', 0, 'L',0, 0, 20, 25, true,'',1);



$pdf->SetFillColor(0, 128, 128);

$pdf->MultiCell(180, 5, '<font size="+5">【参考规范条文】 </font>', 0, 'L',1, 0, 20,130, true,'',1);



$pdf->MultiCell(180, 5, '<font>

1、《防火卷帘、防火门、防火窗施工及验收规范》（GB 50877 - 2014） 5. 3. 8 钢质防火门门框内应充填水泥砂浆。

<br />

2、《建筑装饰装修工程质量验收规范》（GB 50210-2018）6. 3. 7 金属门窗框与墙体之间的缝隙应填嵌饱满，并应采用密封胶密封。

<br />

3、《抹灰砂浆技术规程》（JGJ/ T220-2011） 7.0.9 抹灰层与基层之间及各抹灰层之间应粘结牢固，抹灰层应无脱层、空鼓面积不应大于400cm2，面层应无爆灰和裂缝。</font>', 0, 'L',0, 0, 20, 140, true,'',1);



$pdf->SetFillColor(0, 128, 128);

$pdf->MultiCell(180, 5, '<font size="+5">【合同法律文件关系】 </font>', 0, 'L',1, 0, 20,190, true,'',1);



$pdf->MultiCell(180, 85, '<font>

1、合同对照 

<br />

参照标准:《商品房买卖合同》和合同附件及交付标准等<br />

2、交付文件 

<br />

开发商需提供：《住宅质量保证书》、《住宅使用说明书》、《房地产开发建设项目竣工综合验收合格证》，《竣工验收备案表》等。 

<br />

参照标准： 

<br />

达到交付使用条件的房地产住宅项目，在商品房交付使用时，应当向购买人提供由市城乡建设主管部门统一监制的商品房使用说明书和商品房质量保证书，其中应附房地产开发企业资质证书副本复印件、建设工程竣工验收意见书、建设工程竣工验收备案登记证、住宅工程质量分户验收表、建筑能效测评与标识综合评价表等。</font>', 0, 'L',0, 0, 20, 200, true,'',1);

$pdf->Image('images/sy.png', 20, 40, 140, '', '', 'http://www.cqhaicao.com', '', false, 120);



/*第4页*/

$pdf->AddPage();

$pdf->SetFillColor(0, 128, 128);

$pdf->MultiCell(180, 5, '<font size="+5">【验收参考规范】</font>', 0, 'L',1, 0, 20, 20, true,'',1);



$pdf->MultiCell(180, 5, '<font>《建筑法》； 

<br />

《商品房买卖合同》； 

<br />

《建筑装饰装修工程质量验收规范》（GB 50210-2018）

<br />

《防盗安全门通用技术条件》（GB18065-2008）； 

<br />

《住宅装饰装修工程施工规范》（GB50327-2001）； 

<br />

《抹灰砂浆技术规程》（JGJ/ T220-2011）；

<br />

《城市房地产开发经营管理条例》； 

<br />

《重庆市住宅工程质量分户验收管理暂行规定》；  

<br />

《建筑工程施工质量验收统一标准》GB50300-2013； 

<br />

《建筑地面工程施工质量验收规范》GB50209-2010； 

<br />

《屋面工程质量验收规范》GB50207-2012；  

<br />

《建筑给水排水及采暖工程施工质量验收规范》( GB50242-2013)； 

<br />

《建筑电气工程施工质量验收规范》建标((2002) 82 号； 

<br />

《住宅设计规范》(GB50096-2011)； 

<br />

《钢化玻璃标准》( GB15763.2-2005)等。 </font>', 0, 'L',0, 0, 20, 30, true,'',1);





$pdf->AddPage();

$pdf->MultiCell(180, 5, '<font size="+5">【备注】</font>', 0, 'L',1, 0, 20, 15, true,'',1);



$pdf->MultiCell(180, 5, '<font>1、业主已确认海草验房师按照《海草验房毛坯房查验步骤》执行本套房屋查验工作，确认无漏项，确认对《海草验房第三方房屋验服务报告》呈现的查验结果无异议，如有异议或咨询可联系客服微信或拨打报告下方投诉电话。 

<br />

2、本报告是海草验房依据相关国家房屋验收规范标准及行业标准对该房屋的观感质 

量及使用功能进行专业、客观查验，形成意见和结论。 

<br />

3、海草验房与本验收报告中的业主、施工方及开发商等对象没有利害关系，与有关 

当事人也没有个人利害相关或偏见。建议业主、开发商及施工方等客观正确看待存在的问题并积极沟通解决。 

<br />

4、本报告只对验收当时房屋现状验收结果负责。对本报告未提及项目，海草验房不予评判。

<br />

5、报告中如涉及面积为使用面积（室内净宽净深之积），与建筑面积和套内面积不同，如对建筑面积或套内面积有疑问，建议业主到房管局查询。

<br />

6、房屋查验师按照住宅与建筑的相关规范标准、技术标准和技术规范进行查验。本报告中涉及的建议，供业主装修时参考注意或完善。 

<br />

7、本报告未经同意不得作为商业广告使用及其他用途使用，最终解释权为海草（重庆）科技公司所有。 </font>', 0, 'L',0, 0, 20,25, true,'',1);

$pdf->Image('images/sy.png', 20, 60, 150, '', '', 'http://www.cqhaicao.com', '', false, 120);

$pdf->Image('images/z.png', 120,210, 50, '', '', 'http://www.cqhaicao.com', '', false, 120);

// ---------------------------------------------------------



//Close and output PDF document

ob_clean();

$txt=$pdf->Output('/www/wwwroot/haicao.400060.com/uploads/pdf/example_004.pdf', 'I');

//$txt=$pdf->Output('/www/wwwroot/haicao.400060.com/uploads/pdf/example_004.pdf', 'F');

