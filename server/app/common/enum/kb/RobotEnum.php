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

namespace app\common\enum\kb;

class RobotEnum
{
    // 分享类型
    const SHARE_TYPE_WEB = 1; // 网页
    const SHARE_TYPE_JS  = 2; // JS
    const SHARE_TYPE_OA  = 3; // 公众号
    const SHARE_TYPE_API = 4; // API
    const SHARE_TYPE_QWX = 5; // 企业微信
    const SHARE_TYPE_WX  = 6; // 个人微信
    const SHARE_TYPE_TD  = 7; // 影刀

    // 空回复类型
    const EMPTY_ANSWER_AI   = 1; // 检索为空: AI回复
    const EMPTY_ANSWER_NULL = 2; // 检索为空: 指定文本

    /**
     * @notes 获取密钥前缀
     * @param bool|string $from
     * @return array|string
     */
    public static function getSecretPrefix(bool|string $from = true): array|string
    {
        $desc = [
            self::SHARE_TYPE_WEB => 'web',
            self::SHARE_TYPE_JS  => 'js',
            self::SHARE_TYPE_OA  => 'oa',
            self::SHARE_TYPE_API => 'api',
            self::SHARE_TYPE_QWX => 'qwx',
            self::SHARE_TYPE_WX  => 'wx',
            self::SHARE_TYPE_TD  => 'yd'
        ];
        if(true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }

    /**
     * @notes 获取密钥前缀
     * @param bool|string $from
     * @return array|string
     */
    public static function getSecretDesc(bool|string|int $from = true): array|string
    {
        if (is_numeric($from)) {
            $desc = [
                self::SHARE_TYPE_WEB => '网页',
                self::SHARE_TYPE_JS  => 'JS',
                self::SHARE_TYPE_API => 'API',
                self::SHARE_TYPE_OA  => '公众号',
                self::SHARE_TYPE_QWX => '企业微信',
                self::SHARE_TYPE_WX  => '个人微信',
                self::SHARE_TYPE_TD  => '影刀RPA'
            ];
        } else {
            $desc = [
                'web' => '网页',
                'js'  => 'JS',
                'api' => 'API',
                'oa'  => '公众号',
                'qwx' => '企业微信',
                'wx'  => '个人微信',
                'yd'  => '影刀RPA',
            ];
        }
        if(true === $from) {
            return $desc;
        }
        return $desc[$from] ?? '';
    }


    public static function getPromptTpl($type = ''): string
    {
        if ($type == 'chat') {
            $template = "使用 <Reference></Reference> 标记中的文件内容作为本次对话的参考:

                <Reference>
                [[document]]
                [[files]]
                </Reference>
                
                回答要求:
                    - 如果你不清楚答案，你需要澄清。
                    - 避免提及您是从 <Reference></Reference> 获取的知识。
                    - 保持答案与 <Reference></Reference> 中描述的一致。 
                    - 使用 Markdown 语法优化回答格式。
                    - 使用与问题相同的语言回答。
                    
                问题: [[question]]";
            return str_replace('                ', '', $template);
        } elseif ($type == 'optimize') {
            $template = "作为向量检索助手，你的任务是结合历史记录信息，从多个维度和角度深入分析“原问题”，并据此生成一系列不同版本的“检索词”。
                这些检索词应明确指向与原问题紧密相关的核心概念或信息点，同时保持与原问题相同的语言风格和表达习惯，并且生成最多3条数据。
                通过增加检索词的多样性和语义深度，你的目标是显著提升向量检索的精度和效果，确保检索结果能够更全面、准确地匹配用户需求。
                
                参考 <Example></Example> 标中的示例来完成任务。
                
                <Example>
                <History></History>
                原问题: 介绍下剧情。
                检索词: ['介绍下故事的背景。','故事的主题是什么？','介绍下故事的主要人物。']
                ----------------
                <History>
                Q: 对话背景。
                A: 当前对话聚焦于Python编程语言的基础知识与应用场景探讨。
                </History>
                原问题: 安装步骤。
                检索词: ['Python编程语言安装步骤详解','如何逐步安装Python？','Python安装过程中需要注意什么？']
                ----------------
                <History>
                Q: SQL查询。
                A: 当前对话讨论的是SQL数据库查询语言的使用方法。
                </History>
                原问题: 查询重复数据。
                检索词: ['SQL中如何查询重复数据？','使用SQL语句查找数据库中的重复记录','SQL查询重复数据的具体方法是什么？']
                ----------------
                </Example>
                
                -----
                
                下面是正式的任务：

                历史记录:
                <History>
                {{histories}}
                </History>
                原问题: {{query}}
                检索词: 
                
                请严格遵守格式规则：
                以JSON格式返回检索词：[“检索词1”、“检索词2”、“检索词3”]。您的输出：";
            return str_replace('                ', '', $template);
        } elseif ($type == 'question') {
            $template = "
                回答要求:
                    - 使用 Markdown 语法优化回答格式。
                    - 使用与问题相同的语言回答。
                    
                问题: [[question]]";
            return str_replace('                ', '', $template);
        }
        return "";
    }
}