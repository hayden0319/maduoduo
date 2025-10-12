<template>
    <div class="h-full overflow-hidden p-main">
        <div class="grid lg:grid-cols-2 gap-4 grid-cols-1 h-full">
            <div>
                <el-form label-width="90px">
                    <el-form-item label="测试文本">
                        <el-input
                            v-model="formData.question"
                            type="textarea"
                            rows="20"
                        />
                    </el-form-item>
                    <el-form-item>
                        
                            <div class="w-full flex justify-between">
                                <el-button
                                    :loading="isLock"
                                    :disabled="
                                        formData.question === '' ||
                                        formData.kb_id === 0
                                    "
                                    type="primary"
                                    @click="dataTestFn"
                                >
                                    测试
                                </el-button>

                                <el-button
                                    type="default"
                                    :icon="Search"
                                    @click="showParams = true"
                                >
                                    {{ searchTxt }}
                                </el-button>
                            </div>
                        
                    </el-form-item>
                </el-form>
            </div>

            <div class="px-[10px] py-[5px] h-full overflow-hidden lg:borderLeft">
                <div
                    v-if="answerList.length == 0"
                    class="flex flex-col items-center justify-center h-full"
                >
                    <el-image :src="empty" />
                    <div class="mt-[10px] text-[#5a646e]">
                        测试结果将在这里展示
                    </div>
                </div>
                <el-scrollbar>
                    <h5 
                        v-if="answerList.length != 0" 
                        class="text-2xl font-bold text-tx-regular pb-2"
                    >
                        检索结果 ({{ answerList.length }}) 条
                    </h5>
                    <div v-if="answerList.length != 0">
                        <div
                            v-for="(item, index) in answerList"
                            :key="index"
                            class="p-[10px] border border-solid border-br-light mb-[10px] rounded"
                        >
                            <!-- <el-progress
                                :percentage="Math.abs(item.score / 1) * 100"
                                color="var(--el-text-color-disabled)"
                            >
                                <span>{{
                                    Math.abs(item.score).toFixed(5)
                                }}</span>
                            </el-progress> -->
                            <p class="text-sm text-tx-secondary text-end">
                                <template v-if="item.rerank_score">
                                    <span class="mr-2">重排分数：{{ item.rerank_score }}</span>
                                </template>
                                <template v-if="item.full_score">
                                    <span class="mr-2">全文检索：{{ item.full_score }}</span>
                                </template>
                                <template v-if="item.emb_score">
                                    <span>语义检索：{{item.emb_score }}</span>
                                </template>
                            </p>
                            <div
                                class="text-sm text-tx-secondary mt-[5px] whitespace-pre-line"
                            >
                                {{ item.question }}
                            </div>
                            <div
                                class="text-sm text-tx-secondary whitespace-pre-line"
                            >
                                {{ item.answer }}
                            </div>
                        </div>
                    </div>
                </el-scrollbar>
            </div>
        </div>

        <TestParams
            v-if="showParams"
            :visible="true"
            :default="searchParams"
            @cancel="showParams = false"
            @confirm="handleParams"
        />
    </div>
</template>

<script setup lang="ts">
import { Search } from '@element-plus/icons-vue'
import { useLockFn } from '@/composables/useLockFn'
import { itemDatatest } from '@/api/my_database'
import empty from '@/assets/image/empty.png'
import TestParams from './test-params.vue'

interface SearchParams {
    search_mode: 'similar' | 'full' | 'mix'
    search_tokens: number
    search_similar: number
    ranking_status: number
    ranking_score: number
    ranking_model: string
    optimize_ask: number
    optimize_m_id: string
    optimize_s_id: string
}

const props = defineProps({
    id: {
        type: Number,
        default: 0
    },
    type: {
        type: String,
        default: ''
    }
})

const answerList = ref<any[]>([])

const formData = ref({
    kb_id: props.id,
    question: ""  
})

// 搜索参数
const showParams = ref<boolean>(false)
const searchParams = ref<SearchParams>({
  // 检索参数
    search_mode: "similar",
    search_tokens: 8000,
    search_similar: 0,
    // 重排模型
    ranking_status: 0,
    ranking_score: 0,
    ranking_model: "",
    // 问题优化
    optimize_ask: 0,
    optimize_m_id: "",
    optimize_s_id: ""
})

// 搜索模式
const searchTxt = computed(() => {
    switch (searchParams.value.search_mode) {
        case 'full':
            return '全文检索';
        case 'similar':
            return '语义检索';
        case 'mix':
            return '混合检索';
        default:
            return '';
    }
});

// 处理参数
const handleParams = (data: any) => {
     searchParams.value = {
        ...searchParams.value,
        ...data
    }
    showParams.value = false
}

// 数据测试
const dataTest = async () => {
    const params = {
        ...formData.value,
        ...searchParams.value,
    }
    answerList.value = await itemDatatest(params)
}

const { lockFn: dataTestFn, isLock } = useLockFn(dataTest)
</script>

<style scoped lang="scss">
.borderLeft {
    border-left: 1px solid #e2e2e2;
}
</style>
