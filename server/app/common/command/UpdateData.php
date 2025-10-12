<?php

namespace app\common\command;

use app\common\model\chat\Models;
use app\common\model\chat\ModelsCost;
use app\common\pgsql\KbEmbedding;
use Exception;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class UpdateData extends Command
{
    protected function configure(): void
    {
        $this->setName('update_data')
            ->setDescription('更新Postgre分词');
    }

    protected function execute(Input $input, Output $output): bool
    {
        $model = new KbEmbedding();

        echo "\n\n----------------------------\n";
        echo "温馨提示: 如果你没有替换Postgres容器,再升级,执行本脚本会报错哦~";
        echo "\n---------------------------- \n";

        $index = 1;
        echo "\n开始处理全文分词 ~\n";
        while (true) {
            $uuids = $model->whereNull('phrases')->limit(100)->column('uuid');
            if (!$uuids) {
                echo "全文分词处理完成 ~ \n";
                break;
            }

            echo "执行$index, 本次处理: ". count($uuids)."条\n";
            KbEmbedding::updateTsVector($uuids);
            $index++;
        }

        echo "\n---------------------------- \n";
        echo "所有数据已处理完成, 你可以关闭窗口了 ~ \n";
        echo "\nEND;\n\n";
        return false;
    }
}