<template>
    <el-dialog
        v-model="isVisible"
        title="知识库检索配置"
        width="620"
        :append-to-body="true"
        :close-on-click-modal="false"
        @closed="emits('cancel')"
    >
        <el-form
            ref="formRef"
            :model="formFieldset"
            :rules="rules"
            label-width="110"
            label-position="left"
            status-icon
        >
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
                <el-select class="!w-64" v-model="formFieldset.search_mode" placeholder="请选择搜索模式">
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
                        v-model="formFieldset.search_tokens"
                        :min="100"
                        :max="30000"
                    />
                    <el-input-number
                        class="ml-4"
                        v-model="formFieldset.search_tokens"
                        :min="100"
                        :max="30000"
                    />
                </div>
            </el-form-item>
            <template v-if="isVisibleSearchSimilar">
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
                        v-model="formFieldset.search_similar"
                        size="small"
                        :min="0"
                        :max="1"
                        :step="0.001"
                        :disabled="formFieldset.search_mode !== 'similar'"
                    />
                    <el-input-number
                        class="ml-4"
                        v-model="formFieldset.search_similar"
                        :min="0"
                        :max="1"
                        :step="0.001"
                        :disabled="formFieldset.search_mode !== 'similar'"
                    />
                </div>
            </el-form-item>

            </template>

            <el-divider />

            <el-form-item label="结果重排" prop="ranking_status">
                <template #label="{ label }">
                    <span class="flex items-center">
                        {{ label }}
                        <el-tooltip content="使用重排模型来进行二次排序, 可增强综合排名。" placement="top">
                            <el-icon class="ml-1 cursor-pointer"><InfoFilled /></el-icon>
                        </el-tooltip>
                    </span>
                </template>
                <el-switch v-model="formFieldset.ranking_status" :active-value="1" :inactive-value="0" />
            </el-form-item>
            <el-form-item label="重排权重" prop="ranking_score">
                <template #label="{ label }">
                    <span class="flex items-center">
                        {{ label }}
                        <el-tooltip content="低于这个分数的数据将会被过滤。" placement="top">
                            <el-icon class="ml-1 cursor-pointer"><InfoFilled /></el-icon>
                        </el-tooltip>
                    </span>
                </template>
                <div class="flex items-center">
                    <el-slider
                        class="!w-64"
                        v-model="formFieldset.ranking_score"
                        :min="0"
                        :max="1"
                        :step="0.001"
                    />
                    <el-input-number
                        class="ml-4"
                        v-model="formFieldset.ranking_score"
                        :min="0"
                        :max="1"
                        :step="0.001"
                    />
                </div>
            </el-form-item>
            <el-form-item label="重排模型" prop="ranking_model">
                <ModelPicker
                    class="!w-64"
                    v-model:id="formFieldset.ranking_model"
                    :set-default="false"
                    type="rankingModels"
                    :disabled="false"
                />
            </el-form-item>

            <el-divider />

            <el-form-item label="问题优化" prop="optimize_ask">
                <template #label="{ label }">
                    <span class="flex items-center">
                        {{ label }}
                        <el-tooltip content="综合历史记录和问题, 多维度的生成相似问题, 可增加知识库检索时的精准度。" placement="top">
                            <el-icon class="ml-1 cursor-pointer"><InfoFilled /></el-icon>
                        </el-tooltip>
                    </span>
                </template>
                <el-switch 
                    v-model="formFieldset.optimize_ask" 
                    :active-value="1" 
                    :inactive-value="0" 
                />
            </el-form-item>
            <el-form-item label="优化模型" prop="optimize_m_id">
                <ModelPicker
                    class="!w-64"
                    v-model:id="formFieldset.optimize_m_id"
                    v-model:sub_id="formFieldset.optimize_s_id"
                    :set-default="false"
                    type="chatModels"
                    :disabled="false"
                />
            </el-form-item>

            <div class="flex justify-center pt-3">
                <el-button type="primary" @click="onSubmitForm(formRef)">确认</el-button>
            </div>
        </el-form>
    </el-dialog>
</template>

<script setup lang="ts">
import type {FormInstance, FormRules} from 'element-plus'
import { reactive, ref, computed } from 'vue'
import { InfoFilled } from "@element-plus/icons-vue";

interface FormFields {
    /**
     * 问题优化开关: [0=关, 1=开]
     */
    optimize_ask: number;
    /**
     * 问题优化模型 (主)
     */
    optimize_m_id: string | number;
    /**
     * 问题优化模型(子)
     */
    optimize_s_id: string | number;
    /**
     * 重排模型ID
     */
    ranking_model: string | number;
    /**
     * 重排模型分数
     */
    ranking_score: number;
    /**
     * 重排状态: [0=关,1=开]
     */
    ranking_status: number;
    /**
     * 检索模式: [similar=语义检索, full=全文检索, mix=混合检索]
     */
    search_mode: 'similar' | 'full' | 'mix';
    /**
     * 检索相似度: [0 ~ 1]
     */
    search_similar: number;
    /**
     * 检索引用数: [100-30000]
     */
    search_tokens: number;
}

const props = defineProps<{
    visible: boolean
    default?: Partial<FormFields>  // 改为 Partial 以匹配可选属性
}>()

const emits = defineEmits<{
    (event: 'confirm', data: FormFields): void
    (event: 'cancel'): void
}>()

const isVisible = ref(props.visible)
const formRef = ref<FormInstance>()

// 提供所有必填字段的默认值
const formFieldset = reactive<FormFields>({
    search_mode: "similar",
    search_similar: 0,
    search_tokens: 8000,
    optimize_ask: 0,
    optimize_m_id: '',
    optimize_s_id: '',
    ranking_status: 0,
    ranking_score: 0,
    ranking_model: '',
    ...props.default  // 合并传入的默认值
})

const isVisibleSearchSimilar = computed(() => formFieldset.search_mode === 'similar')
const rules = reactive<FormRules<FormFields>>({})

const searchOptions = [
    { label: "混合检索", value: "mix" },
    { label: "语义检索", value: "similar" },
    { label: "全文检索", value: "full" }
] as const;

const onSubmitForm = async (formEl: FormInstance | undefined) => {
    if (!formEl) return
    await formEl.validate((valid) => {
        if (valid) {
            emits('confirm', formFieldset)
        }
    })
}
</script>