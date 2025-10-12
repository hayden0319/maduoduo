<template>
    <div class="pt-[10px]">
        <el-form-item label="对话模型" prop="model_id">
            <div class="w-80">
                <ModelPicker
                    class="flex-1"
                    v-model:id="formData.model_id"
                    v-model:sub_id="formData.model_sub_id"
                    v-model:configs="modelConfig"
                    :set-default="false"
                    disabled
                />
            </div>
        </el-form-item>

        <el-form-item label="温度属性" required prop="temperature">
            <div class="w-80">
                <el-slider
                    v-model="formData.temperature"
                    :min="modelConfig?.range?.[0]"
                    :max="modelConfig?.range?.[1]"
                    :step="0.1"
                />
                <div class="form-tips">
                    输入{{modelConfig?.range?.[0]}}-{{modelConfig?.range?.[1]}}
                    之间的数值，支持1位小数点，数值越大回答越随机。
                </div>
            </div>
        </el-form-item>

        <el-form-item label="文件解析" prop="support_file">
            <div>
                <el-radio-group v-model="formData.support_file">
                  <el-radio :value="1"> 启用 </el-radio>
                  <el-radio :value="0"> 关闭 </el-radio>
                </el-radio-group>
                <div class="form-tips">开启后对话时支持上传文件，需消耗大量token，按需启用</div>
            </div>
        </el-form-item>

        <el-form-item label="空搜索回复">
            <el-radio-group v-model="formData.search_empty_type">
                <el-radio :value="1"> AI回复</el-radio>
                <el-radio :value="2"> 自定义回复</el-radio>
            </el-radio-group>
        </el-form-item>
        <el-form-item v-if="formData.search_empty_type === 2">
            <div class="w-80">
                <el-input
                    v-model="formData.search_empty_text"
                    placeholder="请输入回复内容，当搜索匹配不上内容时，直接回复填写的内容"
                    type="textarea"
                    :autosize="{ minRows: 6, maxRows: 6 }"
                    :maxlength="1000"
                    show-word-limit
                    clearable
                />
            </div>
        </el-form-item>

        <div class="mx-7 mt-10">
            <el-alert
            title="下面的配置除了『引用上下文』其它的只有当智能体关联了知识库才有效!"
            type="warning"
            />
        </div>

        <div class="mx-8 mt-4">
            <el-divider content-position="left">召回设置</el-divider>
        </div>
        <div>
            <el-form-item label="检索模式" prop="search_mode">
                <template #label="{ label }">
                    <span class="flex items-center">
                        {{ label }}
                        <el-tooltip placement="top">
                            <el-icon class="ml-1 cursor-pointer"><InfoFilled /></el-icon>
                            <template #content>
                                <p>语义检索: 采用向量化模型进行向量检索</p>
                                <p>全文检索: 使用传统的数据库检索方式检索</p>
                                <p>混合检索: 语义检索+全文检索,具有更好的效果,建议搭配重排模型使用。</p>
                                <p>注意: 当您使用全文或混合检索时，是没有语义相似度的，建议您开启重排模型</p>
                            </template>
                        </el-tooltip>
                    </span>
                </template>
                <el-select class="!w-64" v-model="formData.search_mode" placeholder="请选择搜索模式">
                    <el-option
                        v-for="option in searchOptions"
                        :key="option.value"
                        :label="option.label"
                        :value="option.value"
                    />
                </el-select>
            </el-form-item>

            <el-form-item label="引用上限" prop="search_tokens">
                <template #label="{ label }">
                    <span class="flex items-center">
                        {{ label }}
                        <el-tooltip placement="top">
                            <el-icon class="ml-1 cursor-pointer"><InfoFilled /></el-icon>
                            <template #content>
                                <p>该参数表示单次文档从知识库检索最大的Tokens数量</p>
                                <p>说明: 引用越多意味着所需消耗的token越多</p>
                                <p>注意: 切记不要超出模型的最大token限制</p>
                            </template>
                        </el-tooltip>
                    </span>
                </template>
                <div class="flex items-center">
                    <el-slider
                        class="!w-64"
                        v-model="formData.search_tokens"
                        :min="100"
                        :max="30000"
                    />
                    <el-input-number
                        class="ml-4"
                        v-model="formData.search_tokens"
                        :min="100"
                        :max="30000"
                    />
                </div>
            </el-form-item>

            <el-form-item label="引用上下文" prop="context_num">
                <template #label="{ label }">
                    <span class="flex items-center">
                        {{ label }}
                        <el-tooltip placement="top">
                            <el-icon class="ml-1 cursor-pointer"><InfoFilled /></el-icon>
                            <template #content>
                                <p>上下文记忆功能，对话时让大模型知道您前 N次 的对话。</p>
                            </template>
                        </el-tooltip>
                    </span>
                </template>
                <div class="flex items-center">
                    <el-slider
                        class="!w-64"
                        v-model="formData.context_num"
                        :min="0"
                        :max="50"
                        :step="1"
                    />
                    <el-input-number
                        class="ml-4"
                        v-model="formData.context_num"
                        :min="0"
                        :max="50"
                        :step="1"
                    />
                </div>
            </el-form-item>

            <el-form-item label="最低相似度" prop="search_similar">
                <template #label="{ label }">
                    <span class="flex items-center">
                        {{ label }}
                        <el-tooltip placement="top">
                            <el-icon class="ml-1 cursor-pointer"><InfoFilled /></el-icon>
                            <template #content>
                                <p>语义检索的精度，提问检索的内容需要达到该精度才会被引用</p>
                                <ol class="list-decimal pl-4">
                                    <li>高语义相似度(>=0.8): 会检索相关性高的知识，会更准确，同时也容易未命中。</li>
                                    <li>低语义相似度(如0.4): 检索范围更大，更容易匹配知识，但可能回答会不准确。</li>
                                    <li>不同的向量模型检索相似度不一样，具体情况到 『知识库 - 搜索测试』进行调试。</li>
                                    <li>如果您使用的是『全文检索/混合检索』这个值则不会生效，仅对语义检索有效。</li>
                                </ol>
                            </template>
                        </el-tooltip>
                    </span>
                </template>
                <div class="flex items-center">
                    <el-slider
                        class="!w-64"
                        v-model="formData.search_similar"
                        size="small"
                        :disabled="formData.search_mode !== 'similar'"
                        :min="0"
                        :max="1"
                        :step="0.001"
                    />
                    <el-input-number
                        class="ml-4"
                        v-model="formData.search_similar"
                        :disabled="formData.search_mode !== 'similar'"
                        :min="0"
                        :max="1"
                        :step="0.001"
                    />
                </div>
            </el-form-item>
        </div>

        <div class="mx-8 mt-10">
            <el-divider content-position="left">重排设置</el-divider>
        </div>
        <div>
            <el-form-item label="重排开关">
                <div>
                    <el-radio-group v-model="formData.ranking_status">
                        <el-radio :value="0"> 关闭</el-radio>
                        <el-radio :value="1"> 启用</el-radio>
                    </el-radio-group>
                    <div class="form-tips">
                        开启后，则会对从数据库检索的内容进行重新排序(取分数最高的N条数据作为引用)
                    </div>
                </div>
            </el-form-item>
            <el-form-item label="重排分数" v-if="formData.ranking_status === 1">
                <div>
                    <div class="flex items-center">
                        <el-slider
                            class="!w-64"
                            v-model="formData.ranking_score"
                            size="small"
                            :min="0"
                            :max="1"
                            :step="0.001"
                        />
                        <el-input-number
                            class="ml-4"
                            v-model="formData.ranking_score"
                            :min="0"
                            :max="1"
                            :step="0.001"
                        />
                    </div>
                    <div class="form-tips">
                        表示如果数据重排后，分数没有达到该值则会过滤掉。
                    </div>
               </div>
            </el-form-item>

            <el-form-item label="重排模型" prop="vl_models" v-if="formData.ranking_status === 1">
                <div class="w-80">
                    <ModelPicker
                        class="flex-1"
                        type="rankingModels"
                        v-model:id="formData.ranking_model"
                        :set-default="false"
                    />
                </div>
            </el-form-item>
        </div>

        <div class="mx-8 mt-10">
            <el-divider content-position="left">问题优化</el-divider>
        </div>
        <div>
            <el-form-item label="问题优化" prop="optimize_ask">
                <div>
                    <el-switch v-model="formData.optimize_ask" :active-value="1" :inactive-value="0" />
                    <div class="form-tips">
                        综合历史记录和问题, 多维度的生成相似问题, 可增加知识库检索时的精准度。
                    </div>
                 </div>
            </el-form-item>
            <el-form-item label="优化模型" prop="optimize_m_id">
                <ModelPicker
                    class="!w-64"
                    v-model:id="formData.optimize_m_id"
                    v-model:sub_id="formData.optimize_s_id"
                    :set-default="false"
                    type="chatModels"
                    :disabled="false"
                />
            </el-form-item>
        </div>
    </div>
</template>

<script setup lang="ts">
import {InfoFilled} from "@element-plus/icons-vue"
import {useVModel} from '@vueuse/core'

const props = defineProps<{
    modelValue: Record<string, any>
}>()

const emit = defineEmits<{
    (event: 'update:modelValue', value: Record<string, any>): void
}>()

const formData = useVModel(props, 'modelValue', emit)
const modelConfig = ref({
    default: 0,
    range: [0, 1],
    step: 0.1,
    tips: ''
})

const searchOptions: { label: string; value: string }[] = [
    {
        label: "语义检索",
        value: "similar"
    },
    {
        label: "全文检索",
        value: "full"
    },
    {
        label: "混合检索",
        value: "mix"
    }
];

watch(() => modelConfig.value, (v) => {
    console.log(v)
})
</script>
