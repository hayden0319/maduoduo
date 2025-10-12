<?php

namespace app\common\service\ai;

class VectorBaseService
{
    // 这是一个扩充向量维度的函数
    public function expandFeatures(array $vector, int $newDimension): array
    {
        // 获取原始向量的维度
        $currentDimension = count($vector);
        // 如果目标维度小于等于当前维度，直接返回原向量
        if ($newDimension <= $currentDimension) {
            return $vector;
        }
        // 从当前维度开始，循环添加零值，直到达到目标维度
        for ($i = $currentDimension; $i < $newDimension; $i++) {
            $vector[] = pow($vector[$i - $currentDimension], 2);
        }
        // 返回扩充后的向量
        return $vector;
    }

}