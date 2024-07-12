<?php
namespace app\admin\service;


use think\facade\Request;

require_once EXTEND_PATH . 'PHPExcel/Classes/PHPExcel.php';


class PHPExcel
{

    public static function export($filename, $title, $field = [], $database = [], $type = 'Excel2007', $sum = 0)
    {
        $letterArr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'S', 'Y', 'Z'];
        $phpExcel = new \PHPExcel();
        $objSheet = $phpExcel->getActiveSheet();
        $objSheet->setTitle($filename);
        foreach ($title as $key => $value) {
            $objSheet->setCellValue($letterArr[$key] . '1', $value);
            $objSheet->getColumnDimension($letterArr[$key])->setWidth(20);
            $objSheet->getStyle($letterArr[$key] . '1')->getFont()->setBold(true)->setName('Verdana');
            if ($sum == 1) {
                $objSheet->getStyle($letterArr[$key] . '2')->getFont()->setBold(true)->setName('Verdana');
            }

        }

        if ($database) {
            foreach ($database as $k => $v) {
                foreach ($field as $k1 => $v1) {
                    if ($sum == 1) {
                        $objSheet->setCellValue($letterArr[$k1] . ($k + 3), $v[$v1]);
                    } else {
                        $objSheet->setCellValue($letterArr[$k1] . ($k + 2), $v[$v1]);
                    }
                }
            }
        }
        if ($type == 'Excel2007') {
            $filename =  date('Y-m-d H:i:s', time()) . '.xlsx';
        } else if ($type == 'Excel5') {
            $filename =  date('Y-m-d H:i:s', time()) . '.xls';
        }

        $PHPWrite = \PHPExcel_IOFactory::createWriter($phpExcel, $type);
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');
        $PHPWrite->save("php://output");
    }

}