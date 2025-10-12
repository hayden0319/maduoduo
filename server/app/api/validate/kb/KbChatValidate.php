<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\api\validate\kb;

use app\common\validate\BaseValidate;

/**
 * 机器人对话参数验证器
 */
class KbChatValidate extends BaseValidate
{
    protected $rule = [
        'robot_id' => 'require|number',
        'cate_id'  => 'require|number',
        'model'    => 'require',
        'question' => 'require|max:8000'

    ];

    protected $message = [
        'robot_id.require'  => '请选择机器人',
        'cate_id.require'   => '请选择会话窗口',
        'model.require'     => '请配置对话模型',
        'question.require'  => '提问问题不可为空',
        'question.max'      => '问题太长了缩减到8000个字符内吧'
    ];
}