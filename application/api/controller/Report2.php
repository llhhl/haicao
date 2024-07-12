<?php

// +----------------------------------------------------------------------

// | cms_api [ iActing ]

// +----------------------------------------------------------------------

// | Copyright (c) 2018~2088 https://www.ijiandian.com All rights reserved.

// +----------------------------------------------------------------------

// | Data: 2020/5/19 0019 11:32

// +----------------------------------------------------------------------

// | Author: iActing <758246061@qq.com>

// +----------------------------------------------------------------------



namespace app\api\controller;



// 报表管理





use app\admin\model\ReportListModel;

use app\api\model\ReportList;

use app\api\service\Pdf;

use app\api\service\Token;

use app\api\validate\ReportValidate;

use diypdf\TCPDF;



class Report extends BaseController

{



    public function download()

    {



        $id = input('id');

        if (!isset($id) || empty($id)) {

            returnErrors("ID参数必填");

        }



        $result = ReportListModel::where('id', $id)->find();





        if (!empty($result['pdf_url'])) {



            $data = [

                'url' => http_url().$result['pdf_url']

            ];

            returnSuccess("成功", $data);

        } else {



            $userInfo = \app\api\model\ReportUser::where('id', $result['user_id']['id'])->find();

            // 重新生成

            // $file_folder = 'uploads/image/' .$phone.'_'.$time.'_'.$type_str.'_' . $user_info['job_number'].'/';

            $spdf = new \app\api\controller\Report();

            if($result['house_type']==1){

                $type_str = 'MP';

            }else{

                $type_str = 'JQ';

            }

            $report_url = '/uploads/pdf/' . $result['client_mobile']. '_'.$result['create_time']. '_' .$type_str.'_'. $result['job_number'] . ".pdf";

            $result['pdf_url'] = $report_url;

            $result['inspector'] = $userInfo['real_name'];

            $result['house_id'] = date("YmdHis");

            //$result['create_time'] = time();





            $content = object_array($result['content']);

            //$content = json_decode($result['content']);

            //$content = json_decode($content);

            $content = json_decode($content, true);



//            return dump($content);exit();

            if($result['moban']==2){

                $spdf->create_pdf1($result,$content);// 宜居

            }else if($result['moban']==1){

                $spdf->create_pdf2($result,$content);// 海草

            }else{

                $spdf->create_pdf($result,$content);//

            }



            ReportListModel::where('id', $id)->update([

                'pdf_url' => $report_url

            ]);



            $data = [

                'url' => http_url().$report_url

            ];

            returnSuccess("成功", $data);

        }

    }

    // 报告搜索下载

    public function search()

    {

        $user_id = Token::getCurrentTokenUserId();

        $user_info = \app\api\model\ReportUser::where('id', $user_id)->find();

        if ($user_info['status'] != 1) {

            returnErrors("帐户被禁用");

        }



        $key = input('keyword');

        if (!isset($key) || empty($key)) {

            returnErrors("请输入关键词");

        }



        $result = ReportList::where('client_mobile', 'like', "%" . $key . "%")->field("id,client_username,client_mobile,house_address,house_type,pdf_url,create_time,house_name")->order('create_time desc')->select();

        $data = [

            'download' => $user_info['download'],

            'list' => $result

        ];

        if (empty($result)) {

            returnSuccess("暂无相关数据~", $data);

        }

        returnSuccess("成功", $data);

    }



    // 我的报告

    public function my_report()

    {

        $user_id = Token::getCurrentTokenUserId();

        $user_info = \app\api\model\ReportUser::where('id', $user_id)->find();

        if ($user_info['status'] != 1) {

            returnErrors("帐户被禁用");

        }

        $result = ReportList::where('user_id', $user_id)->field("id,client_username,client_mobile,house_address,house_type,pdf_url,create_time,house_name")->order('create_time desc')->select();

        $data = [

            'download' => $user_info['download'],

            'list' => $result

        ];

        if (empty($result)) {

            returnSuccess("您还没有验房报告哦~", $data);

        }

        returnSuccess("成功", $data);

    }



    public function insert1()

    {

        $params = (new ReportValidate())->goCheck('insert');





        $form_data = $params['result_data']['form_data'];

        $report_list = $params['result_data']['report_list'];



        $userInfo = \app\api\model\ReportUser::where('mobile', $form_data['inspector_mobile'])->find();



        // 报告内容保存地址 ROOT_PATH . '/uploads/ftp/example_002.pdf'

        //$erport_url = '/uploads/pdf/' . rand_str(12) . '_' . $userInfo['id'] . "_pdf.pdf";





        // 处理图片



        //$json = json_encode($params, true);

        //file_put_contents('new12222_binggege.txt', $json . "\r\n", FILE_APPEND);



        if($form_data['type']==1){

            $type_str = 'MP';

        }else{

            $type_str = 'JQ';

        }



        $user_info = \app\api\model\ReportUser::where('mobile', $form_data['inspector_mobile'])->find();



        $path = 'uploads/image/' .$form_data['client_mobile'].'_'.date("Y-m-d").'_'.$type_str.'_' . $user_info['job_number'].'/';//  文件夹 uploads/image/13013090543_1001 命名为 手机号_员工编号

        $report_list = $this->handle($report_list,$path);



//         returnSuccess("",$report_list);



        $up = new Upload();

        $client_signature = http_url().'/'. $up->uploadssss($form_data['signImagereal'],$path);













        $content = json_encode($report_list);



        $form_data['house_type'] = $form_data['type'];

        unset($form_data['type']);

        $form_data['client_signature'] = $client_signature;

        unset($form_data['signImagereal']);

        $form_data['remark'] = $form_data['bz'];



        $form_data['user_id'] = $userInfo['id'];

        $form_data['area_id'] = $userInfo['area_id'];

        $form_data['job_number'] = $userInfo['job_number'];

        $form_data['create_time'] = time();

        $form_data['content'] = $content;

        $form_data['pdf_url'] = '';

        unset($form_data['bz']);

        unset($form_data['inspector_mobile']);



        $reportListModel = new ReportList();

        $result = $reportListModel::create($form_data);



        $form_data['inspector'] = $userInfo['real_name'];

        $form_data['house_id'] = date("YmdHis");



        // $this->create_pdf($form_data, $report_list);

        if($result){

            returnSuccess("成功");

        }

        returnErrors("失败");

    }



    // 处理图片资源

    public function handle($report_list,$path){

        $up = new Upload();

        $list = [];

        if(is_array($report_list)){

            foreach ($report_list as $k=>$v){

//            returnSuccess("",$report_list);

                $item=[];

                $item['title'] = $v['title'];

                $item['desc'] = $v['desc'];

//            if(!empty($v['images'])){

                foreach ($v['images'] as $i=>$n){

                    //file_put_contents('new1_binggege.txt', $n . "\r\n", FILE_APPEND);



                    $src= $up->uploadssss($n,$path);

                    //file_put_contents('new1_binggege.txt', $src . "\r\n", FILE_APPEND);

                    //return $src;

                    $item['images'][$i] =http_url().'/'. $src;



                }

//            }



                $list[$k]=$item;



            }

        }





        return $list;

    }







    // 模板1

    public function create_pdf($data, $content)

    {



        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);





// set default header data

        // $pdf->SetHeaderData("", "","海草验房|中国第三方专业验房平台                                     ", "");

        $pdf->SetFont('stsongstdlight', '', 14, '', true);

// set header and footer fonts

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        $pdf->setFooterFont(Array('', '', PDF_FONT_SIZE_DATA));



// set default monospaced font

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins

        $pdf->SetMargins(10, 10, 10);

        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



// set auto page breaks

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set image scale factor

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {

            require_once(dirname(__FILE__) . '/lang/eng.php');

            $pdf->setLanguageArray($l);

        }



// ---------------------------------------------------------



// set font

//$pdf->SetFont('stsongstdlight', '', 11);



// add a page

        $pdf->AddPage();



//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $client_username = $data["client_username"];

        $inspector = $data['inspector'];

        $create_time = $data['create_time'];

        $type = ["精装房", "毛坯房"];

        $house_type = $type[$data['house_type']];

        $house_id = $data['house_id'];

        $job_number = $data['job_number'];





// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

        $pdf->Image('images/logo.png', 30, 15, 40, '', '', '', '', false, 120);

        $pdf->MultiCell(75, 5, '<font size="+10">海草验房服务报告</font>', 0, 'R', 0, 20, 80, 25, true, '', 1);

        $pdf->MultiCell(125, 25, "<font size='+1'>编&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号 ： $house_id - $job_number", 0, 'R', 0, 20, 80, 45, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>房屋验收类型：	 $house_type", 0, 'R', 0, 20, 40, 60, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>委&nbsp;托&nbsp;方 ：	 $client_username", 0, 'R', 0, 20, 40, 80, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>验&nbsp;房&nbsp;师：	 $inspector", 0, 'R', 0, 20, 40, 100, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>验 房 日 期：	 $create_time", 0, 'R', 0, 20, 40, 120, true, '', 1);

        $pdf->Image('images/z.png', 70, 80, 50, '', '', '', '', false, 120);

        $pdf->MultiCell(175, 20, '<font size="+1">   200000+业主的共同选择   我们只验房不装修', 0, 'R', 0, 20, 0, 135, true, '', 1);



        $pdf->Image('images/ma.jpg', 40, 150, 120, '', '', '', '', false, 110);

        $pdf->MultiCell(175, 20, '<font size="+1">关于我们', 0, 'L', 0, 20, 20, 220, true, '', 1);

        $pdf->MultiCell(170, 60, '<font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 海草®验房是海草（重庆）科技有限公司旗下品牌，海草公司提供是第三方

房屋质量检测服务、室内空气检测与治理服务、装修监理服务、美缝服务，公司是由多家老牌验房公司资源整合统一运营强强联合成立。公司有50余名资深验房师，跨越多个省市，专业打造美缝、除甲醛等家居产业链服务，公司结合国家建筑装饰标准制定的毛坯房22步查验项目，精装房39步查验项目，推动了行业标准化、产业化发展进程。', 0, 'L', 0, 20, 20, 223, true, '', 1);

     

        /*第二页*/

        $pdf->Image('images/sy.png', 20, 40, 140, '', '', '', '', false, 120);

        $pdf->setCellHeightRatio(1.6);

        $pdf->MultiCell(600, 20, '<table border="1" cellspacing="0" >

  <tr >

    <td width="100" valign="center" ><p align="center" >委 托 人 </p></td>

    <td width="200" valign="center" ><p align="center" >' . $data['client_username'] . ' </p></td>

    <td width="100" valign="center" ><p align="center" >电   话 </p></td>

    <td width="200" valign="center" ><p align="center" >' . $data['client_mobile'] . ' </p></td>

  </tr>

  <tr >

    <td valign="center" width="100" ><p align="center" >市/区/县 </p></td>

    <td  valign="center" ><p align="center" >' . $data['house_address'] . ' </p></td>

    <td  valign="center" ><p align="center" >楼   盘 </p></td>

    <td  valign="center" ><p align="center" >' . $data['house_name'] . ' </p></td>

  </tr>

  <tr >

    <td  valign="center" ><p align="center" >房   号 </p></td>

    <td valign="center" ><p align="center" >' . $data['house_number'] . ' </p></td>

    <td valign="center" ><p align="center" >房屋面积 </p></td>

    <td valign="center" ><p align="center" >' . $data['house_area'] . ' </p></td>

  </tr>

  <tr >

    <td  valign="top" colspan="4" ><p align="justify" >'.'应业主委托，海草验房派专业验房师对该处房屋当前建筑质量进行查验，查验结果如下：该房屋共检查出'.$data['num'].'处问题，所有问题均已在室内相关位置标记。'.' </p></td>

  </tr>

</table>', 0, 'C', 0, 20, 20, 15, true, '', 1);

// 第一栏--------------------------------------------------------固定

//$pdf->MultiCell(180, 20, '<font size="+1">以下是验房问题的图文描述（人员上传图文，软件生成，本行不显示）', 0, 'L', 0, 20, 20, 80, true,'',1);

        $pdf->setCellHeightRatio(1.8);



//        returnSuccess("s",$content[0]['images'] );



        // 固定第一栏数据

        $content_html = '';



        foreach ($content as $i => $n) {

            $content_html .= '<tr >

    <td colspan="2" valign="middle" bgcolor="#008080" align="center" border="1">

	<br />' . $content[$i]['title'] . '</td></tr> <tr ><td border="1" colspan="2" valign="top" align="left">问题汇总：<br />

' . $content[$i]['desc'] . '</td></tr> ';

            $content_html .= '<tr >';

            $k = 0;

            if(!empty($n['images'])){



                foreach ($n['images'] as $l => $m) {

                    $k = $k + 1;

                    $content_html .= '<td valign="middle"  align="center" style=""><img src="' . $m . '"  width="296"  style="border: 1px solid #0A0E11;padding: 2px;display: block"> </td>';

                    if ($k % 2 == 0) {

                        $content_html .= "</tr><tr>";

                    }



                }

            }





            if ($k % 2 == 0) {

                $content_html = substr($content_html, 0, -4);

            } else {

                $content_html .= "<td></td></tr>";



            }

        }



//

//        if ($k % 2 == 0) {

//           //$content_html = substr($content_html, 0, -4);

//        } else {

//            $content_html .= "</tr>";

//

//        }



        //echo("固定第一栏table".$content_html);die;

        //echo("固定第一栏table".$content_html);die;



        // 剩余其他

//        $content_other_html ='<tr>';

//        foreach ($content as $k=>$v){

//            if($k>0){

//                returnSuccess("其余table",$v );

//            }

//        }





        $html = <<<EOD

<table border="0" width="600" >

       

    {$content_html}

    

    </table>

EOD;

//如果要对html指定 宽度  writeHTMLCell更方便

        $pdf->setCellHeightRatio(1.4);





        $pdf->SetXY(21, 59, $rtloff = false);

        $pdf->writeHTML($html, true, false, true, false, 'C');

        $pdf->Image('images/sy.png', 20, 40, 140, '', '', '', '', false, 120);

        /*验房信息页*/

/////////////////////////////







        $html = <<<EOD

EOD;

        $pdf->setCellHeightRatio(1.4);

        $pdf->SetXY(21, 12, $rtloff = false);

//如果要对html指定 宽度  writeHTMLCell更方便

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Image('images/sy.png', 20, 40, 140, '', '', '', '', false, 120);





        /*第三页*/

        $pdf->AddPage();

        $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【毛坯房常见问题整改建议】 </font>', 0, 'L', 1, 0, 20, 15, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>1、金属门门框与墙有缝隙：先填充水泥砂浆外侧再用耐候胶封闭。<br />



2、墙面空鼓：使用切割机切割抹灰层空鼓边缘处，在用凿子凿干净空鼓处，并做基层清理，然后用水养护好，再重新抹灰找平；  

<br />

3、墙面抹灰层开裂：敲掉重新找平 需挂网处理；

<br />

4、地面抹灰层开裂：表面涂抹环氧胶泥：涂抹环氧胶泥前，先将裂缝附近 80～100mm 宽度范围内的灰尘、浮渣用压缩空气吹净，或用钢丝刷、砂纸、毛刷清除干净并洗净，油污可用二甲苯或丙酮擦洗一遍，如表面潮湿，应用喷灯烘烤干燥、预热，以保证环氧胶泥与混凝土粘结良好。若基层难以干燥，则用环氧煤焦油胶泥涂抹。涂抹时，用毛刷或刮板均匀蘸取胶泥，并涂刮在裂缝表面。

<br />

5、墙面返碱： 需要将墙面返碱的区域的面层完全铲除干净，看到墙体基层为止。最好再使用具有一定浓度的草酸，加入适量的温水进行液体调制，用刷子均匀的涂抹在返碱的墙面上。利用化学上的酸碱中和反应原理，来解决墙面返碱问题。但反应完成后，用水从返碱的墙面上方向下冲洗，待墙面干净后。则需要开窗进行通风，让墙面进行充分的干燥，再来进行墙面的修补工作。<br />

6、玻璃划痕：一般不能处理，若划痕较多建议更换玻璃。 </font>', 0, 'L', 0, 0, 20, 25, true, '', 1);





 $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【精装房常见问题整改建议】 </font>', 0, 'L', 1, 0, 20, 130, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>1、墙面漆层裂缝：可用细砂纸进行打磨，然后再用相同颜色的漆重新涂刷。<br />



2、卫生间和阳台积水，倒坡： 建议把砖翘起来重新找好坡度，不然后期排水不畅，还是会积水甚至渗水。

<br />

3、局部墙面起皮、起鼓：铲除起皮的部分，小范围重新刮腻子。可能会存在色差问题，可以考虑修补腻子后把有起皮、起鼓的整面墙粉刷乳胶漆。

<br />

4、卫生间漏水：需查明漏水原因进行处理。

<br />

5、玻璃划痕：一般不能处理，若划痕较多建议更换玻璃。</font>', 0, 'L', 0, 0, 20, 140, true, '', 1);



        $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【参考规范条文】 </font>', 0, 'L', 1, 0, 20, 190, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>

1、《防火卷帘、防火门、防火窗施工及验收规范》（GB 50877 - 2014）5. 3. 8 钢质防火门门框内应充填水泥砂浆。

<br />

2、《建筑装饰装修工程质量验收规范》（GB 50210-2018）6. 3. 7 金属门窗框与墙体之间的缝隙应填嵌饱满，并应采用密封胶密封。



<br />

3、《抹灰砂浆技术规程》（JGJ/ T220-2011）<br />

 7.0.9 抹灰层与基层之间及各抹灰层之间应粘结牢固，抹灰层应无脱层、空鼓面积不应大于400cm2，面层应无爆灰和裂缝。<br />

4、《建筑装饰装修工程质量验收规范》（GB 50210-2018）6. 3. 7 金属门窗框与墙体之间的缝隙应填嵌饱满，并应采用密封胶密封。<br />

5、《建筑装饰装修工程质量验收规范》GB50210-2018 12.2.3 水性涂料涂饰工程应涂饰均匀、粘接牢固，不得漏涂、透底、开裂、起皮和掉粉。<br />

6、《建筑地面工程施工质量验收规范》GB 50209-2010 7．1．8 木、竹面层的允许偏差不超过2-3mm。</font>', 0, 'L', 0, 0, 20, 205, true, '', 1);

   $pdf->AddPage();

      $pdf->SetFillColor(0, 128, 128);

       $pdf->MultiCell(180, 5, '<font size="+5">【合同法律文件关系】 </font>', 0, 'L', 1, 0, 20, 45, true, '', 1);



        $pdf->MultiCell(180, 85, '<font>

1、合同对照 

<br />

参照标准:《商品房买卖合同》和合同附件及交付标准等<br />

2、交付文件 

<br />

毛坯房开发商需提供：《住宅质量保证书》、《住宅使用说明书》、《房地产开发建设项目竣工综合验收合格证》，《竣工验收备案表》等<br />

精装房开发商需提供：《住宅质量保证书》、《住宅使用说明书》、《房地产开发建设项目竣工综合验收合格证》，《竣工验收备案表》及《管线分布竣工图》等。  

<br />

参照标准： 

<br />

达到交付使用条件的房地产住宅项目，在商品房交付使用时，应当向购买人提供由市城乡建设主管部门统一监制的商品房使用说明书和商品房质量保证书，其中应附房地产开发企业资质证书副本复印件、建设工程竣工验收意见书、建设工程竣工验收备案登记证、住宅工程质量分户验收表、建筑能效测评与标识综合评价表等。</font>', 0, 'L', 0, 0, 20, 55, true, '', 1);

        $pdf->Image('images/sy.png', 20, 40, 140, '', '', '', '', false, 120);



        /*第4页*/

       

        $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【验收参考规范】</font>', 0, 'L', 1, 0, 20,140, true, '', 1);



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

《住宅工程质量分户验收管理暂行规定》；  

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

《钢化玻璃标准》( GB15763.2-2005)等。 </font>', 0, 'L', 0, 0, 20, 150, true, '', 1);





        $pdf->AddPage();

        $pdf->MultiCell(180, 5, '<font size="+5">【备注】</font>', 0, 'L', 1, 0, 20, 15, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>1、业主已确认海草验房师按照《海草验房房屋查验步骤》执行本套房屋查验工作，确认无漏项，确认对《海草验房服务报告》呈现的查验结果无异议，如有异议或咨询可联系客服微信或拨打报告下方投诉电话。 

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

7、本报告未经同意不得作为商业广告使用及其他用途使用，最终解释权为海草（重庆）科技公司所有。 </font>', 0, 'L', 0, 0, 20, 25, true, '', 1);

        $pdf->Image('images/sy.png', 20, 60, 150, '', '', '', '', false, 120);



        $pdf->MultiCell(75, 20, "<font size='+3'>验 房 师：	 $inspector", 0, 'R', 0, 20, 110, 220, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+2'> $create_time", 0, 'R', 0, 20, 110, 230, true, '', 1);



        $pdf->Image($data['client_signature'], 20, 192, 50, '', '', '', '', false, 120);

        $pdf->Image('images/z.png', 145, 190, 50, '', '', '', '', false, 120);

        $pdf->MultiCell(120, 5, '<font size="+2">【业主签名】：</font>', 0, 'L', 0, 0, 20, 190, true, '', 1);

// ---------------------------------------------------------



//Close and output PDF document

        ob_end_clean();

        // 输出到浏览器

//        $txt=$pdf->Output('/www/wwwroot/haicao.400060.com/uploads/pdf/example_004.pdf', 'I');

        // 保存到本地



        $txt = $pdf->Output(ROOT_PATH . $data['pdf_url'], 'F');

        return true;

    }



    // 模板2

    public function create_pdf1($data, $content)

    {

        require_once('./tcpdf_min/tcpdf.php');

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);





// set default header data

        // $pdf->SetHeaderData("", "","海草验房|中国第三方专业验房平台                                     ", "");

        $pdf->SetFont('stsongstdlight', '', 14, '', true);

// set header and footer fonts

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        $pdf->setFooterFont(Array('', '', PDF_FONT_SIZE_DATA));



// set default monospaced font

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins

        $pdf->SetMargins(10, 10, 10);

        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



// set auto page breaks

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set image scale factor

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {

            require_once(dirname(__FILE__) . '/lang/eng.php');

            $pdf->setLanguageArray($l);

        }



// ---------------------------------------------------------



// set font

//$pdf->SetFont('stsongstdlight', '', 11);



// add a page

        $pdf->AddPage();



//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $client_username = $data["client_username"];

        $inspector = $data['inspector'];

        $create_time = $data['create_time'];

        $type = ["精装房", "毛坯房"];

        $house_type = $type[$data['house_type']];

        $house_id = $data['house_id'];

        $job_number = $data['job_number'];



// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

        $pdf->Image('images/logo1.png', 30, 15, 40, '', '', '', '', false, 120);

        $pdf->MultiCell(75, 5, '<font size="+10">宜居验房服务报告</font>', 0, 'R', 0, 20, 80, 25, true, '', 1);

        $pdf->MultiCell(125, 25, "<font size='+1'>编&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号 ： $house_id - $job_number", 0, 'R', 0, 20, 80, 45, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>房屋验收类型：	 $house_type", 0, 'R', 0, 20, 40, 60, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>委&nbsp;托&nbsp;方 ：	 $client_username", 0, 'R', 0, 20, 40, 80, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>验&nbsp;房&nbsp;师：	 $inspector", 0, 'R', 0, 20, 40, 100, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>验 房 日 期：	 $create_time", 0, 'R', 0, 20, 40, 120, true, '', 1);

        $pdf->Image('images/z1.png', 70, 80, 50, '', '', '', '', false, 120);

        $pdf->MultiCell(175, 20, '<font size="+1">持证验房师&nbsp;&nbsp;18种专业验房工具&nbsp;&nbsp;我们只验房不装修', 0, 'R', 0, 20, 0, 150, true, '', 1);



        $pdf->Image('images/ma1.jpg', 40, 160, 120, '', '', '', '', false, 110);

        $pdf->MultiCell(175, 20, '<font size="+1">关于我们', 0, 'L', 0, 20, 20, 240, true, '', 1);

        $pdf->MultiCell(175, 20, '<font size="+1">宜居验房是重庆宜居科技有限公司旗下品牌，宜居公司是从事验房、甲醛检测、甲醛祛除、美缝施工的专业公司，公司有专业技术人员52名，结合国家建筑装饰标准制定的毛坯房20步查验项目，精装房39步查验项目。', 0, 'L', 0, 20, 20, 250, true, '', 1);

        $pdf->AddPage();

        /*第二页*/

        $pdf->Image('images/sy1.png', 20, 40, 140, '', '', '', '', false, 120);

        $pdf->setCellHeightRatio(1.6);

        $pdf->MultiCell(600, 20, '<table border="1" cellspacing="0" >

  <tr >

    <td width="100" valign="center" ><p align="center" >委 托 人 </p></td>

    <td width="200" valign="center" ><p align="center" >' . $data['client_username'] . ' </p></td>

    <td width="100" valign="center" ><p align="center" >电   话 </p></td>

    <td width="200" valign="center" ><p align="center" >' . $data['client_mobile'] . ' </p></td>

  </tr>

  <tr >

    <td valign="center" width="100" ><p align="center" >市/区/县 </p></td>

    <td  valign="center" ><p align="center" >' . $data['house_address'] . ' </p></td>

    <td  valign="center" ><p align="center" >楼   盘 </p></td>

    <td  valign="center" ><p align="center" >' . $data['house_name'] . ' </p></td>

  </tr>

  <tr >

    <td  valign="center" ><p align="center" >房   号 </p></td>

    <td valign="center" ><p align="center" >' . $data['house_number'] . ' </p></td>

    <td valign="center" ><p align="center" >房屋面积 </p></td>

    <td valign="center" ><p align="center" >' . $data['house_area'] . ' </p></td>

  </tr>

  <tr >

  <td  valign="top" colspan="4" ><p align="justify" >'.'应业主委托，宜居验房派专业验房师对该处房屋当前建筑质量进行查验，查验结果如下：该房屋共检查出'.$data['num'].'处问题，所有问题均已在室内相关位置标记。'.' </p></td>

  </tr>

</table>', 0, 'C', 0, 20, 20, 15, true, '', 1);

// 第一栏--------------------------------------------------------固定

//$pdf->MultiCell(180, 20, '<font size="+1">以下是验房问题的图文描述（人员上传图文，软件生成，本行不显示）', 0, 'L', 0, 20, 20, 80, true,'',1);

        $pdf->setCellHeightRatio(1.8);



//        returnSuccess("s",$content[0]['images'] );



        // 固定第一栏数据

        $content_html = '';



        foreach ($content as $i => $n) {

            $content_html .='<tr >

    <td border="0" colspan="2" valign="middle" bgcolor="#FFF" align="center" >

	<br /></td></tr>';





            $content_html .= '<tr >

    <td border="1" colspan="2" valign="middle" bgcolor="#008080" align="center" >

	<br />' . $content[$i]['title'] . '</td></tr> <tr ><td border="1" colspan="2" valign="top" align="left">问题汇总：<br />' . $content[$i]['desc'] . '</td></tr> ';

            $content_html .= '<tr >';

            $k = 0;

            foreach ($n['images'] as $l => $m) {

                $k = $k + 1;

                $content_html .= '<td valign="middle"  align="center" height="220"><img src="' . $m . '" width="294"  style="border: 1px solid #0A0E11;"></td>';

                if ($k % 2 == 0) {

                    $content_html .= "</tr><tr>";

                }



            }

            if ($k % 2 == 0) {

                $content_html = substr($content_html, 0, -4);

            } else {

                $content_html .= "<td></td></tr>";



            }

        }



//

//        if ($k % 2 == 0) {

//           //$content_html = substr($content_html, 0, -4);

//        } else {

//            $content_html .= "</tr>";

//

//        }



        //echo("固定第一栏table".$content_html);die;

        //echo("固定第一栏table".$content_html);die;



        // 剩余其他

//        $content_other_html ='<tr>';

//        foreach ($content as $k=>$v){

//            if($k>0){

//                returnSuccess("其余table",$v );

//            }

//        }





        $html = <<<EOD

<table border="0" width="600" >

       

    {$content_html}

    

    </table>

EOD;

//如果要对html指定 宽度  writeHTMLCell更方便

        $pdf->setCellHeightRatio(1.4);





        $pdf->SetXY(21, 59, $rtloff = false);

        $pdf->writeHTML($html, true, false, true, false, 'C');

        $pdf->Image('images/sy1.png', 20, 40, 140, '', '', '', '', false, 120);

        /*验房信息页*/

/////////////////////////////







        $html = <<<EOD

EOD;

        $pdf->setCellHeightRatio(1.4);

        $pdf->SetXY(21, 12, $rtloff = false);

//如果要对html指定 宽度  writeHTMLCell更方便

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Image('images/sy1.png', 20, 40, 140, '', '', '', '', false, 120);





        /*第三页*/

        $pdf->AddPage();

        $pdf->SetFillColor(0, 255, 255);

        $pdf->MultiCell(180, 5, '<font size="+5">【毛坯房通用问题整改建议】 </font>', 0, 'L', 1, 0, 20, 15, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>1、金属门门框与墙有缝隙：先填充水泥砂浆外侧再用耐候胶封闭。<br />



2、墙面空鼓：使用切割机切割抹灰层空鼓边缘处，在用凿子凿干净空鼓处，并做基层清理，然后用水养护好，再重新抹灰找平； 

<br />

3、墙面抹灰层开裂：敲掉重新找平 需挂网处理； 

<br />

4、地面抹灰层开裂：表面涂抹环氧胶泥：涂抹环氧胶泥前，先将裂缝附近 80～100mm 宽度范围内的灰尘、浮渣用压缩空气吹净，或用钢丝刷、砂纸、毛刷清除干净并洗净，油污可用二甲苯或丙酮擦洗一遍，如表面潮湿，应用喷灯烘烤干燥、预热，以保证环氧胶泥与混凝土粘结良好。若基层难以干燥，则用环氧煤焦油胶泥涂抹。涂抹时，用毛刷或刮板均匀蘸取胶泥，并涂刮在裂缝表面。

<br />

5、墙面返碱： 需要将墙面返碱的区域的面层完全铲除干净，看到墙体基层为止。最好再使用具有一定浓度的草酸，加入适量的温水进行液体调制，用刷子均匀的涂抹在返碱的墙面上。利用化学上的酸碱中和反应原理，来解决墙面返碱问题。但反应完成后，用水从返碱的墙面上方向下冲洗，待墙面干净后。则需要开窗进行通风，让墙面进行充分的干燥，再来进行墙面的修补工作。

<br />

6、玻璃划痕：一般不能处理，若划痕较多建议更换玻璃。 </font>', 0, 'L', 0, 0, 20, 25, true, '', 1);



        $pdf->SetFillColor(0, 255, 255);

        $pdf->MultiCell(180, 5, '<font size="+5">【参考规范条文】 </font>', 0, 'L', 1, 0, 20, 130, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>

1、《防火卷帘、防火门、防火窗施工及验收规范》（GB 50877 - 2014） 5. 3. 8 钢质防火门门框内应充填水泥砂浆。

<br />

2、《建筑装饰装修工程质量验收规范》（GB 50210-2018）6. 3. 7 金属门窗框与墙体之间的缝隙应填嵌饱满，并应采用密封胶密封。

<br />

3、《抹灰砂浆技术规程》（JGJ/ T220-2011） 7.0.9 抹灰层与基层之间及各抹灰层之间应粘结牢固，抹灰层应无脱层、空鼓面积不应大于400cm2，面层应无爆灰和裂缝。</font>', 0, 'L', 0, 0, 20, 140, true, '', 1);



        $pdf->SetFillColor(0, 255, 255);

        $pdf->MultiCell(180, 5, '<font size="+5">【合同法律文件关系】 </font>', 0, 'L', 1, 0, 20, 190, true, '', 1);



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

达到交付使用条件的房地产住宅项目，在商品房交付使用时，应当向购买人提供由市城乡建设主管部门统一监制的商品房使用说明书和商品房质量保证书，其中应附房地产开发企业资质证书副本复印件、建设工程竣工验收意见书、建设工程竣工验收备案登记证、住宅工程质量分户验收表、建筑能效测评与标识综合评价表等。</font>', 0, 'L', 0, 0, 20, 200, true, '', 1);

        $pdf->Image('images/sy1.png', 20, 40, 140, '', '', '', '', false, 120);



        /*第4页*/

        $pdf->AddPage();

        $pdf->SetFillColor(0, 255, 255);

        $pdf->MultiCell(180, 5, '<font size="+5">【验收参考规范】</font>', 0, 'L', 1, 0, 20, 20, true, '', 1);



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

《钢化玻璃标准》( GB15763.2-2005)等。 </font>', 0, 'L', 0, 0, 20, 30, true, '', 1);





        $pdf->AddPage();

        $pdf->SetFillColor(0, 255, 255);

        $pdf->MultiCell(180, 5, '<font size="+5">【备注】</font>', 0, 'L', 1, 0, 20, 15, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>1、业主已确认宜居验房师按照《宜居验房精装房查验步骤》执行本套房屋查验工作，确认无漏项，确认对《宜居验房第三方房屋验收咨询报告》呈现的查验结果无异议。如有异议或咨询可联系客服微信或拨打报告下方投诉电话。 

<br />

2、本报告是宜居验房依据相关国家房屋验收规范标准及行业标准对该房屋的观感质量及使用功能进行专业、客观查验，形成意见和结论。  

<br />

3、宜居验房与本验收报告中的业主、施工方及开发商等对象没有利害关系，与有关当事人也没有个人利害相关或偏见。建议业主、开发商及施工方等客观正确看待存在的问题并积极沟通解决。

<br />

4、本报告只对验收当时房屋现状验收结果负责。对本报告未提及项目，宜居验房不予评判。

<br />

5、报告中如涉及面积为使用面积（室内净宽净深之积），与建筑面积和套内面积不同，如对建筑面积或套内面积有疑问，建议业主到房管局查询。

<br />

6、房屋查验师按照住宅与建筑的相关规范标准、技术标准和技术规范进行查验。本报告中涉及的建议，供业主装修时参考注意或完善。 

<br />

7、本报告未经同意不得作为商业广告使用及其他用途使用，最终解释权为重庆宜居科技公司所有。 </font>', 0, 'L', 0, 0, 20, 25, true, '', 1);

        $pdf->Image('images/sy1.png', 20, 60, 150, '', '', '', '', false, 120);



        $pdf->MultiCell(75, 20, "<font size='+3'>验 房 师：	 $inspector", 0, 'R', 0, 20, 110, 220, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+2'> $create_time", 0, 'R', 0, 20, 110, 230, true, '', 1);



        $pdf->Image($data['client_signature'], 20, 192, 50, '', '', '', '', false, 120);

        $pdf->Image('images/z1.png', 145, 190, 50, '', '', '', '', false, 120);

        $pdf->MultiCell(120, 5, '<font size="+2">【业主签名】：</font>', 0, 'L', 0, 0, 20, 190, true, '', 1);

// ---------------------------------------------------------



//Close and output PDF document

        ob_end_clean();

        // 输出到浏览器

//        $txt=$pdf->Output('/www/wwwroot/haicao.400060.com/uploads/pdf/example_004.pdf', 'I');

        // 保存到本地



        $txt = $pdf->Output(ROOT_PATH . $data['pdf_url'], 'F');

        return true;

    }



    // 模板3

    public function create_pdf2($data, $content)

    {



        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);





// set default header data

        // $pdf->SetHeaderData("", "","海草验房|中国第三方专业验房平台                                     ", "");

        $pdf->SetFont('stsongstdlight', '', 14, '', true);

// set header and footer fonts

        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

        $pdf->setFooterFont(Array('', '', PDF_FONT_SIZE_DATA));



// set default monospaced font

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);



// set margins

        $pdf->SetMargins(10, 10, 10);

        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);

        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);



// set auto page breaks

        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



// set image scale factor

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {

            require_once(dirname(__FILE__) . '/lang/eng.php');

            $pdf->setLanguageArray($l);

        }



// ---------------------------------------------------------



// set font

//$pdf->SetFont('stsongstdlight', '', 11);



// add a page

        $pdf->AddPage();



//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $client_username = $data["client_username"];

        $inspector = $data['inspector'];

        $create_time = $data['create_time'];

        $type = ["精装房", "毛坯房"];

        $house_type = $type[$data['house_type']];

        $house_id = $data['house_id'];

        $job_number = $data['job_number'];



// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

//Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)

        $pdf->Image('images/logo.png', 30, 15, 40, '', '', '', '', false, 120);

        $pdf->MultiCell(75, 5, '<font size="+10">海草验房服务报告</font>', 0, 'R', 0, 20, 80, 25, true, '', 1);

        $pdf->MultiCell(125, 25, "<font size='+1'>编&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号 ： $house_id - $job_number", 0, 'R', 0, 20, 80, 45, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>房屋验收类型：	 $house_type", 0, 'R', 0, 20, 40, 60, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>委&nbsp;托&nbsp;方 ：	 $client_username", 0, 'R', 0, 20, 40, 80, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>验&nbsp;房&nbsp;师：	 $inspector", 0, 'R', 0, 20, 40, 100, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+3'>验 房 日 期：	 $create_time", 0, 'R', 0, 20, 40, 120, true, '', 1);

        $pdf->Image('images/z.png', 70, 80, 50, '', '', '', '', false, 120);

        $pdf->MultiCell(175, 20, '<font size="+1">   200000+业主的共同选择   我们只验房不装修', 0, 'R', 0, 20, 0, 135, true, '', 1);



        $pdf->Image('images/ma.jpg', 40, 150, 120, '', '', '', '', false, 110);

        $pdf->MultiCell(175, 20, '<font size="+1">关于我们', 0, 'L', 0, 20, 20, 220, true, '', 1);

        $pdf->MultiCell(170, 60, '<font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 海草®验房是海草（重庆）科技有限公司旗下品牌，海草公司提供是第三方

房屋质量检测服务、室内空气检测与治理服务、装修监理服务、美缝服务，公司是由多家老牌验房公司资源整合统一运营强强联合成立。公司有50余名资深验房师，跨越多个省市，专业打造美缝、除甲醛等家居产业链服务，公司结合国家建筑装饰标准制定的毛坯房22步查验项目，精装房39步查验项目，推动了行业标准化、产业化发展进程。', 0, 'L', 0, 20, 20, 223, true, '', 1);



        /*第二页*/

        $pdf->Image('images/sy.png', 20, 40, 140, '', '', '', '', false, 120);

        $pdf->setCellHeightRatio(1.6);

        $pdf->MultiCell(600, 20, '<table border="1" cellspacing="0" >

  <tr >

    <td width="100" valign="center" ><p align="center" >委 托 人 </p></td>

    <td width="200" valign="center" ><p align="center" >' . $data['client_username'] . ' </p></td>

    <td width="100" valign="center" ><p align="center" >电   话 </p></td>

    <td width="200" valign="center" ><p align="center" >' . $data['client_mobile'] . ' </p></td>

  </tr>

  <tr >

    <td valign="center" width="100" ><p align="center" >市/区/县 </p></td>

    <td  valign="center" ><p align="center" >' . $data['house_address'] . ' </p></td>

    <td  valign="center" ><p align="center" >楼   盘 </p></td>

    <td  valign="center" ><p align="center" >' . $data['house_name'] . ' </p></td>

  </tr>

  <tr >

    <td  valign="center" ><p align="center" >房   号 </p></td>

    <td valign="center" ><p align="center" >' . $data['house_number'] . ' </p></td>

    <td valign="center" ><p align="center" >房屋面积 </p></td>

    <td valign="center" ><p align="center" >' . $data['house_area'] . ' </p></td>

  </tr>

  <tr >

    <td  valign="top" colspan="4" ><p align="justify" >'.'应业主委托，海草验房派专业验房师对该处房屋当前建筑质量进行查验，查验结果如下：该房屋共检查出'.$data['num'].'处问题，所有问题均已在室内相关位置标记。'.' </p></td>

  </tr>

</table>', 0, 'C', 0, 20, 20, 15, true, '', 1);

// 第一栏--------------------------------------------------------固定

//$pdf->MultiCell(180, 20, '<font size="+1">以下是验房问题的图文描述（人员上传图文，软件生成，本行不显示）', 0, 'L', 0, 20, 20, 80, true,'',1);

        $pdf->setCellHeightRatio(1.8);



//        returnSuccess("s",$content[0]['images'] );



        // 固定第一栏数据

        $content_html = '';



        foreach ($content as $i => $n) {

            $content_html .= '<tr >

    <td colspan="2" valign="middle" bgcolor="#008080" align="center" border="1">

	<br />' . $content[$i]['title'] . '</td></tr> <tr ><td border="1" colspan="2" valign="top" align="left">问题汇总：<br />

' . $content[$i]['desc'] . '</td></tr> ';

            $content_html .= '<tr >';

            $k = 0;

            if(!empty($n['images'])){



                foreach ($n['images'] as $l => $m) {



                    $k = $k + 1;

                    $content_html .= '<td valign="middle"  align="center" style=""><img src="' . $m . '"  width="294"  style="border: 1px solid #0A0E11;"> </td>';

                    if ($k % 2 == 0) {

                        $content_html .= "</tr><tr>";

                    }



                }

            }





            if ($k % 2 == 0) {

                $content_html = substr($content_html, 0, -4);

            } else {

                $content_html .= "<td></td></tr>";



            }

        }



//

//        if ($k % 2 == 0) {

//           //$content_html = substr($content_html, 0, -4);

//        } else {

//            $content_html .= "</tr>";

//

//        }



        //echo("固定第一栏table".$content_html);die;

        //echo("固定第一栏table".$content_html);die;



        // 剩余其他

//        $content_other_html ='<tr>';

//        foreach ($content as $k=>$v){

//            if($k>0){

//                returnSuccess("其余table",$v );

//            }

//        }





        $html = <<<EOD

<table border="0" width="600" >

       

    {$content_html}

    

    </table>

EOD;

//如果要对html指定 宽度  writeHTMLCell更方便

        $pdf->setCellHeightRatio(1.4);





        $pdf->SetXY(21, 59, $rtloff = false);

        $pdf->writeHTML($html, true, false, true, false, 'C');

        $pdf->Image('images/sy.png', 20, 40, 140, '', '', '', '', false, 120);

        /*验房信息页*/

/////////////////////////////







        $html = <<<EOD

EOD;

        $pdf->setCellHeightRatio(1.4);

        $pdf->SetXY(21, 12, $rtloff = false);

//如果要对html指定 宽度  writeHTMLCell更方便

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Image('images/sy.png', 20, 40, 140, '', '', '', '', false, 120);





        /*第三页*/

        $pdf->AddPage();

        $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【毛坯房常见问题整改建议】 </font>', 0, 'L', 1, 0, 20, 15, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>1、金属门门框与墙有缝隙：先填充水泥砂浆外侧再用耐候胶封闭。<br />



2、墙面空鼓：使用切割机切割抹灰层空鼓边缘处，在用凿子凿干净空鼓处，并做基层清理，然后用水养护好，再重新抹灰找平；  

<br />

3、墙面抹灰层开裂：敲掉重新找平 需挂网处理；

<br />

4、地面抹灰层开裂：表面涂抹环氧胶泥：涂抹环氧胶泥前，先将裂缝附近 80～100mm 宽度范围内的灰尘、浮渣用压缩空气吹净，或用钢丝刷、砂纸、毛刷清除干净并洗净，油污可用二甲苯或丙酮擦洗一遍，如表面潮湿，应用喷灯烘烤干燥、预热，以保证环氧胶泥与混凝土粘结良好。若基层难以干燥，则用环氧煤焦油胶泥涂抹。涂抹时，用毛刷或刮板均匀蘸取胶泥，并涂刮在裂缝表面。

<br />

5、墙面返碱： 需要将墙面返碱的区域的面层完全铲除干净，看到墙体基层为止。最好再使用具有一定浓度的草酸，加入适量的温水进行液体调制，用刷子均匀的涂抹在返碱的墙面上。利用化学上的酸碱中和反应原理，来解决墙面返碱问题。但反应完成后，用水从返碱的墙面上方向下冲洗，待墙面干净后。则需要开窗进行通风，让墙面进行充分的干燥，再来进行墙面的修补工作。<br />

6、玻璃划痕：一般不能处理，若划痕较多建议更换玻璃。 </font>', 0, 'L', 0, 0, 20, 25, true, '', 1);





        $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【精装房常见问题整改建议】 </font>', 0, 'L', 1, 0, 20, 130, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>1、墙面漆层裂缝：可用细砂纸进行打磨，然后再用相同颜色的漆重新涂刷。<br />



2、卫生间和阳台积水，倒坡： 建议把砖翘起来重新找好坡度，不然后期排水不畅，还是会积水甚至渗水。

<br />

3、局部墙面起皮、起鼓：铲除起皮的部分，小范围重新刮腻子。可能会存在色差问题，可以考虑修补腻子后把有起皮、起鼓的整面墙粉刷乳胶漆。

<br />

4、卫生间漏水：需查明漏水原因进行处理。

<br />

5、玻璃划痕：一般不能处理，若划痕较多建议更换玻璃。</font>', 0, 'L', 0, 0, 20, 140, true, '', 1);



        $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【参考规范条文】 </font>', 0, 'L', 1, 0, 20, 190, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>

1、《防火卷帘、防火门、防火窗施工及验收规范》（GB 50877 - 2014）5. 3. 8 钢质防火门门框内应充填水泥砂浆。

<br />

2、《建筑装饰装修工程质量验收规范》（GB 50210-2018）6. 3. 7 金属门窗框与墙体之间的缝隙应填嵌饱满，并应采用密封胶密封。



<br />

3、《抹灰砂浆技术规程》（JGJ/ T220-2011）<br />

 7.0.9 抹灰层与基层之间及各抹灰层之间应粘结牢固，抹灰层应无脱层、空鼓面积不应大于400cm2，面层应无爆灰和裂缝。<br />

4、《建筑装饰装修工程质量验收规范》（GB 50210-2018）6. 3. 7 金属门窗框与墙体之间的缝隙应填嵌饱满，并应采用密封胶密封。<br />

5、《建筑装饰装修工程质量验收规范》GB50210-2018 12.2.3 水性涂料涂饰工程应涂饰均匀、粘接牢固，不得漏涂、透底、开裂、起皮和掉粉。<br />

6、《建筑地面工程施工质量验收规范》GB 50209-2010 7．1．8 木、竹面层的允许偏差不超过2-3mm。</font>', 0, 'L', 0, 0, 20, 205, true, '', 1);

        $pdf->AddPage();

        $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【合同法律文件关系】 </font>', 0, 'L', 1, 0, 20, 45, true, '', 1);



        $pdf->MultiCell(180, 85, '<font>

1、合同对照 

<br />

参照标准:《商品房买卖合同》和合同附件及交付标准等<br />

2、交付文件 

<br />

毛坯房开发商需提供：《住宅质量保证书》、《住宅使用说明书》、《房地产开发建设项目竣工综合验收合格证》，《竣工验收备案表》等<br />

精装房开发商需提供：《住宅质量保证书》、《住宅使用说明书》、《房地产开发建设项目竣工综合验收合格证》，《竣工验收备案表》及《管线分布竣工图》等。  

<br />

参照标准： 

<br />

达到交付使用条件的房地产住宅项目，在商品房交付使用时，应当向购买人提供由市城乡建设主管部门统一监制的商品房使用说明书和商品房质量保证书，其中应附房地产开发企业资质证书副本复印件、建设工程竣工验收意见书、建设工程竣工验收备案登记证、住宅工程质量分户验收表、建筑能效测评与标识综合评价表等。</font>', 0, 'L', 0, 0, 20, 55, true, '', 1);

        $pdf->Image('images/sy.png', 20, 40, 140, '', '', '', '', false, 120);



        /*第4页*/



        $pdf->SetFillColor(0, 128, 128);

        $pdf->MultiCell(180, 5, '<font size="+5">【验收参考规范】</font>', 0, 'L', 1, 0, 20,140, true, '', 1);



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

《住宅工程质量分户验收管理暂行规定》；  

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

《钢化玻璃标准》( GB15763.2-2005)等。 </font>', 0, 'L', 0, 0, 20, 150, true, '', 1);





        $pdf->AddPage();

        $pdf->MultiCell(180, 5, '<font size="+5">【备注】</font>', 0, 'L', 1, 0, 20, 15, true, '', 1);



        $pdf->MultiCell(180, 5, '<font>1、业主已确认海草验房师按照《海草验房房屋查验步骤》执行本套房屋查验工作，确认无漏项，确认对《海草验房服务报告》呈现的查验结果无异议，如有异议或咨询可联系客服微信或拨打报告下方投诉电话。 

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

7、本报告未经同意不得作为商业广告使用及其他用途使用，最终解释权为海草（重庆）科技公司所有。 </font>', 0, 'L', 0, 0, 20, 25, true, '', 1);

        $pdf->Image('images/sy.png', 20, 60, 150, '', '', '', '', false, 120);



        $pdf->MultiCell(75, 20, "<font size='+3'>验 房 师：	 $inspector", 0, 'R', 0, 20, 110, 220, true, '', 1);

        $pdf->MultiCell(75, 20, "<font size='+2'> $create_time", 0, 'R', 0, 20, 110, 230, true, '', 1);



        $pdf->Image($data['client_signature'], 20, 192, 50, '', '', '', '', false, 120);

        $pdf->Image('images/z.png', 145, 190, 50, '', '', '', '', false, 120);

        $pdf->MultiCell(120, 5, '<font size="+2">【业主签名】：</font>', 0, 'L', 0, 0, 20, 190, true, '', 1);

// ---------------------------------------------------------



//Close and output PDF document

        ob_end_clean();

        // 输出到浏览器

//        $txt=$pdf->Output('/www/wwwroot/haicao.400060.com/uploads/pdf/example_004.pdf', 'I');

        // 保存到本地



        $txt = $pdf->Output(ROOT_PATH . $data['pdf_url'], 'F');

        return true;

    }







}