<?php

/**
 * 导出控制器
 * Class pdfExportController
 * @author bruce
 */
class pdfExportController{


    /**
     * 导出pdf 视图
     * @author bruce
     */
    public function dataExportPdf(){

        $where = [];
        $data = $this->Phone_model->getRow('*',$where);

        //调用服务
        $this->viewGeneration($data);
        exit();
    }


    /**
     * pdf 视图生成
     * @param $data
     * @return mixed
     * @remark 简单协助分析分为3块：
     * 第一块：html 页面属性，需要具备会写样式（不会找web），
     * 第二：将data 中的值赋给对应内容中，
     * 第三：调用pdf 核心类，生成pdf 文件链接提供下载。
     * @author bruce
     */
    public function viewGeneration($data = []){
        $html_content='<html>
        <title>test</title>
        <body>
            <div class="body">
                <div class="bigtitle">xxxx标题</div>
                <div  class="bigtitle">xxx报告单</div>
                <div  class="bigtitle down_border">MTHFR(KOI 6666C)xxxx检测报告单</div>
                <div class="title down_border">
                    <div class="title" style="width:100%;">
                    <div class="box">
                        <div class="bold">姓名:</div>
                        <div>'.$data['name'].'</div>
                    </div>
                    <div class="box">
                        <div class="bold">性别:</div>
                        <div>'.$data['sex'].'</div>
                    </div>
                    <div class="box">
                        <div class="bold">年龄:</div>
                        <div>'.$data['age'].'</div>
                    </div>
                    </div>
                   
                    <div class="title" style="width:100%;">
                    <div class="box">
                        <div class="bold">创建时间:</div>
                        <div>'.$data['create_time'].'</div>
                    </div>
                    <div class="box">
                        <div class="bold">修改时间:</div>
                        <div>'.$data['update_time'].'</div>
                    </div>
                   
                    </div>
                </div>
                <div class="clear down_border" >
                    <div class="left_title">MTHFR(KOI 6666C)xxxx检测结果:</div>
                </div>
                <div class="title down_border">
                    <div class="jc_box">
                    <div>序号</div>
                    </div>
                    <div  class="jc_box">
                    <div>检测1</div>
                    </div>
                    <div  class="jc_box">
                    <div>检测2</div>
                    </div>
                    <div  class="jc_box" style="text-align:center">
                    <div>检测3</div>
                    </div>
                </div>
                <div class="down_border title" >
                    <div class="title" style="width:100%;">
                    <div  class="jc_box">
                        <div>序号</div>
                    </div>
                    <div  class="jc_box">
                        <div>检测4</div>
                    </div>
                    <div  class="jc_box">
                        <div>检测5</div>
                    </div>
                    <div  class="jc_box" style="text-align:center">
                        <div>'.$data['xxx'].'</div>
                    </div>
                    </div>
                    <div class="title" style="width:100%;">
                    <div  class="jc_box">
                        <div>序号</div>
                    </div>
                    <div  class="jc_box">
                        <div>检测6</div>
                    </div>
                    <div  class="jc_box">
                        <div>检测7</div>
                    </div>
                    <div  class="jc_box" style="text-align:center">
                        <div>'.$data['xxx'].'</div>
                    </div>
                    </div>
                </div>
                <div class="title"><text class="bold">结论：</text>'.$data['xxx'].'</div>
                <div class="clear"><div class="left_title bold"> 建议:</div></div>
                <div class="surround_box title">
                    <div class="title" style="width:100%;">
                    <div style="float: left;width:80%;text-align: center;">女性</div>
                    <div>男性</div>
                    </div>
                    <div class="title" style="width:100%;">
                    <div class="jg_box">检测1</div>
                    <div class="jg_box">检测2</div>
                    <div class="jg_box">检测3</div>
                    <div class="jg_box">检测4</div>
                    <div class="jg_box">检测5</div>
                    </div>
                    <div class="title" style="width:100%;">
                    <div  class="jg_box">结果</div>
                    <div  class="jg_box">666</div>
                    <div  class="jg_box">666</div>
                    <div  class="jg_box">666</div>
                    <div  class="jg_box">666</div>
                    </div>
                    <div class="title" style="width:100%;">
                    <div class="jg_box">结论</div>
                    <div class="jg_box">555</div>
                    <div class="jg_box">555</div>
                    <div class="jg_box">555</div>
                    <div class="jg_box">555</div>
                    </div>
                </div>
                <div class="clear" style="clear:both;"><div class="left_title bold" >说明:</div></div>
                <div class="title">
                '.$data['content'].'
                </div>
                <div class="clear"><div class="left_title bold">备注:</div></div>
                <div class="title">
                1、xxxxxx1。
                2、xxxxxx2。
                3、xxxxxx3。
                </div>
                <div class="title footer">
                    <div class="box">
                    <div class="bold">内容1:</div>
                    <div>'.$data['content_1'].'</div>
                    </div>
                    <div class="box">
                    <div class="bold">内容2:</div>
                    <div>'.$data['content_2'].'</div>
                    </div>
                    <div class="box">
                    <div class="bold">内容3:</div>
                    <div>'.$data['content_3'].'</div>
                    </div>
                </div>
            </div>
        </body>
        <style>
            .body{
            font-size: 20px;
            width: 95%;
            margin: 0 auto;
            }
            .bigtitle{
            width: 100%;
            font-size: 25px;
            font-weight: bold;
            margin: 5px auto;
            text-align: center;
            }
            .clear{
            width: 100%;
            margin-top: 10px;
            clear:both;
            }
            .title{
                width: 100%;
                margin:3px auto;
                display: inline-block;
            }
            .left_title{
            margin-top: 4px;
            margin-left: 0px;
            }
            .title .box{
            width: 33.3%;
            float: left;
            display: flex;
            }
            .title .box :first-child{
            width:40%;
            }
            .title .box :last-child{
            width:55%;
            }
            .jc_box{
            width:25%;
            word-break:break-all;
            float: left;
            }
            .bold{
            font-weight:bold;
            }
            .jg_box{
            width:19%;
            word-break:break-all;
            float: left;
            }
            .down_border{
            border-bottom: 1px solid #000;
            }
            .surround_box{
            border:1px solid #000;
            }
            .footer{
            margin-top:10px;
            }
        </style>
    </html>';
        $mpdf = new \Mpdf\Mpdf(['mode'=>'utf-8','format' => 'A4',]);

        $mpdf->SetDisplayMode('fullpage');
        //自动分析录入内容字体
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        // 自定义文章pdf文件存储路径

        $fileUrl = "upload/".date("Ymd")."/jcpdf".time().".pdf";
        if (!file_exists("upload/".date("Ymd"))) {
            mkdir("upload/".date("Ymd"),0777,true);
        }
        //默认 以html为标准分析写入内容
        $mpdf->WriteHTML($html_content);
        // 文件生成指令
        $mpdf->Output($fileUrl);

        $url = "http://".$_SERVER['HTTP_HOST']."/".$fileUrl;

        //返回链接提供下载
        return  [
            'code' => 200,
            'msg'  => 'success',
            'data' => $url
        ];

    }



}