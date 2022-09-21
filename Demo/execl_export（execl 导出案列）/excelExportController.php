<?php

/**
 * 导出控制器
 * Class pdfExportController
 * @author bruce
 */
class excelExportController{


    /**
     * 导出简洁版本-1
     * @remark 代码案列
     * @author bruce
     */
    public function excelExport(){
        //这里数据查询，需要替换
        $data = $this->Question_model->getList('*',[]);

        $filename = '导出';
        $filename = iconv('utf-8', 'gbk', $filename);
        header('Content-type:application/vnd.ms-excel;charset=gbk');
        header("Content-Disposition:filename=" . $filename . ".csv");
        $col_name = "序号,名称,性别,年龄,创建时间\r\n";
        echo iconv('utf-8', 'gbk', $col_name);
        if (!empty($data)) {
            foreach ($data as $item) {
                echo iconv('utf-8','gbk',$item['id']).",";
                echo iconv('utf-8','gbk',$item['name']).",";
                echo iconv('utf-8','gbk',$item['sex']).",";
                echo iconv('utf-8','gbk',$item['age']).",";
                echo iconv('utf-8','gbk',date('Y-m-d H:i:s',$item['create_time'])).",";
                echo "\r\n";
            }
        }
        exit();

    }


    /**
     * 导出服务版
     * @remark 导出调用服务版
     * @author bruce
     */
    public function excelExportTwo(){

        $data = [
            //二维数组
        ];
        $title = ['序号','名称','性别','年龄','创建时间'];
        $filename = '导出';

        $service = new Excel();
        $service->export_csv($data,$title,$filename);

        exit();

    }


}