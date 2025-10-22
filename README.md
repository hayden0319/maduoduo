# Maduoduo 开放平台

> 面向 AI 内容生产的多端数字化解决方案，现已全面开源，诚邀全球开发者共同打造。

Maduoduo 是一套覆盖 **服务端 + 管理后台 + PC 前台 + 移动前台** 的完整产品形态。项目最初作为企业内部产品交付，如今以开源方式发布，希望在生成式 AI、数字人、内容创作等场景下，为创业团队与个人开发者提供可扩展、可二次开发的基础设施。

---

## 核心特性
- **一体化架构**：ThinkPHP 6 + Vue 3 + Nuxt 3 + UniApp，原生支持 Web / 小程序 / App 全端覆盖。
- **低代码能力**：内置代码生成器，常见业务模块一键生成，显著降低开发和交付成本。
- **企业级模块**：权限体系、支付、消息通知、素材管理、知识库、数字人合成等模块即开即用。
- **可视化安装与部署**：支持向导式安装、Docker 本地体验、可扩展的配置中心。
- **开放协作**：完整的目录和数据库文档，持续迭代路线公开透明。

## 技术栈概览
- **服务端**：PHP 8、ThinkPHP 6、MySQL、Redis、Composer
- **管理后台（`admin/`）**：TypeScript、Vue 3、Vite、Element Plus、Pinia
- **PC 前台（`pc/`）**：Nuxt 3、Vue 3、TailwindCSS
- **移动前台（`uniapp/`）**：Vue 3、UniApp、Vite
- **工程化**：Yarn / pnpm、ESLint、Prettier、Docker、GitHub Actions（规划中）

## 仓库结构
```text
├─ server/                         # 服务端（ThinkPHP 6）
│  ├─ app/
│  │  ├─ adminapi/                 # 管理后台接口
│  │  ├─ api/                      # 前台接口
│  │  ├─ common/                   # 通用服务、帮助方法
│  │  └─ ...                       # 事件、队列、中间件等
│  ├─ public/
│  │  ├─ index.php                 # PHP 应用入口
│  │  ├─ admin/                    # 已编译的管理后台静态资源
│  │  ├─ pc/                       # 已编译的 PC 前台静态资源
│  │  └─ mobile/                   # 已编译的移动端静态资源
│  └─ .env                         # 服务端环境配置（上线请自行维护）
│
├─ admin/                          # 管理后台前端源码
│  ├─ .env.development.example     # 开发环境模板
│  └─ .env.production.example      # 生产环境模板
│
├─ pc/                             # PC 前台源码
│  └─ .env.example                 # 环境变量模板
│
├─ uniapp/                         # 移动端前台源码（H5 / 小程序 / App）
│  ├─ .env.development.example
│  └─ .env.production.example
│
├─ docker/                         # Docker 配置
│  ├─ config/                      # 容器配置
│  ├─ data/                        # 容器数据（勿随意删除）
│  ├─ log/                         # 容器日志
│  └─ docker-compose.yml           # 编排文件
└─ README.md
```

---

## 环境准备
- **Node.js**：推荐 `>= 16.0.0`，建议使用 `nvm` 管理多版本。
- **包管理器**：推荐 `yarn`，首次请执行 `npm install -g yarn`。
- **PHP**：建议 `>= 8.0`，需启用常用扩展（curl、mbstring、gd、openssl 等）。
- **Composer**：用于管理服务端依赖。
- **数据库 & 缓存**：MySQL 5.7+/8.0+、Redis 5+。

若计划使用 Docker，请确保已安装 `Docker Engine` 和 `docker compose`。

---

## 快速开始

### 1. 克隆仓库
```bash
git clone https://github.com/<your-org>/maduoduo.git
cd maduoduo
```

### 2. （可选）自动化前端初始化
我们提供统一的初始化脚本，支持在 `admin/`、`pc/`、`uniapp/` 目录中快捷完成依赖安装和基础配置。

```bash
cd uniapp          # 亦可进入 pc/ 或 admin/
npm run init
```

执行后根据提示完成以下动作：
1. 是否安装依赖（已安装可选择 `n` 跳过）。
2. 填写服务器域名（例如 `https://example.com`）。
3. 选择要启动的客户端（H5 / 小程序 / 管理后台等）。

当脚本提示成功后，即完成该子项目的初始化，可直接进行二次开发。

> ⚠️ 使用自动化脚本后，可跳过后续手工复制 `.env` 的步骤，仅需关注「打包 & 部署」章节。

---

## 手动初始化指引

### 服务端（`server/`）
```bash
cd server
cp .env.example .env        # 若仓库未提供示例，可自行创建
composer install
php think migrate:run       # 初始化数据库
php think run               # 启动开发服务器，默认端口 8000
```

> 若使用 Nginx/Apache，请将站点根目录指向 `server/public`。

### PC Web 前台（`pc/`）
```bash
cd pc
cp .env.example .env                # 可按需复制为 .env.development / .env.production
yarn install                        # 或 pnpm install
yarn dev                            # 开发环境
yarn build                          # 非 SSR 生产构建
yarn build:ssr                      # SSR 构建
```

- `.env`：全局配置，通常无需修改。
- `.env.development`：设置开发态接口地址 `NUXT_API_URL='https://your-dev-domain'`。
- `.env.production`：设置生产态接口地址；为空时默认使用当前域名。
- `NUXT_SSR`：设置任意值启用 SSR。

### 移动端前台（`uniapp/`）
```bash
cd uniapp
cp .env.development.example .env.development
cp .env.production.example .env.production
yarn install
yarn dev:h5              # H5 调试
yarn dev:mp-weixin       # 微信小程序调试
yarn build:h5            # H5 生产构建
yarn build:mp-weixin     # 小程序打包
```

在 VS Code 中可直接运行命令行；HBuilderX 用户可通过「运行 → 运行到浏览器 / 小程序模拟器」完成调试。若使用 Apple M 系芯片且出现 `esbuild` 相关报错，可复制 `node_modules/esbuild-darwin-arm64` 并重命名为 `esbuild-darwin-64` 进行兼容。

### 管理后台（`admin/`）
```bash
cd admin
cp .env.development.example .env.development
cp .env.production.example .env.production
yarn install
yarn dev               # 开发环境
yarn build             # 生产构建
```

- `VITE_APP_BASE_URL`：配置接口域名，生产环境留空将跟随当前域名。
- 如构建或依赖安装失败，请删除 `yarn.lock` 与 `node_modules` 后重试。

---

## Docker 本地体验
```bash
cd docker
docker compose up -d
```

默认开放端口：
- 管理后台：http://127.0.0.1:20221/admin/login
- PC 前台：http://127.0.0.1:20221/pc/
- 移动前台：http://127.0.0.1:20221/mobile/

> ⚠️ Docker 编排仅适用于本地体验与开发调试，请勿直接用于生产环境。部署生产环境时，请准备独立的数据库与存储服务，并根据业务规划拆分容器。

---

## 生产部署建议
1. 准备高可用的 MySQL、Redis、对象存储服务。
2. 完成 `server/.env` 中的数据库、缓存、队列、对象存储等配置。
3. 将前端产物部署至 CDN 或 Web 服务器（`yarn build` / `yarn build:ssr` 输出文件见各自 `dist/` 或 `.output/`）。
4. 使用 Supervisor / Systemd 等工具守护 PHP 队列与定时任务。
5. 配置 HTTPS 与 Web 防火墙，定期备份数据库和上传文件。

---

## 贡献指南
非常欢迎大家参与共建！我们建议遵循以下流程：

1. **Fork & Clone**：Fork 仓库并克隆到本地，配置上游远程 `git remote add upstream`.
2. **创建分支**：为每个功能或修复创建独立分支，例如 `feature/xxxx`、`fix/xxxx`。
3. **保障质量**：
   - 遵循各模块既定的代码风格和 Lint 规范。
   - 为修复/新增功能补充必要的单元测试或端到端用例。
   - 更新相关文档（README、变更日志、接口文档等）。
4. **提交 PR**：编写清晰的标题和描述，关联 Issue，说明变更动机、测试结果和影响范围。
5. **充分沟通**：在 Issue 中与维护者讨论设计与实现细节，收到 Review 评论后积极反馈或补充改动。

我们没有设定繁琐的门槛，只需要尊重彼此、保持高质量产出。感谢每一位贡献者的时间与心力！

---

## 路线图 & 里程碑
- [ ] 完善自动化测试与 CI/CD 流程
- [ ] 发布数据库迁移与初始化脚本
- [ ] 输出多语言与主题扩展方案
- [ ] 发布插件市场规范与示例插件
- [ ] 持续完善文档与社区案例

欢迎在 Issues 中投票或提出新的路线建议。

---

## 社区与支持
- **Bug 反馈 & 功能建议**：请通过 GitHub Issues 提交，尽量附上日志与复现步骤。
- **安全问题**：请发送邮件至 `security@maduoduo.com`（临时邮箱），我们将在 48 小时内响应。
- **交流渠道**：正在筹备 Discord/微信群，敬请关注后续公告。

---

## 许可证
本项目采用 **MIT License**，详见 `server/LICENSE.txt`。您可以自由使用、修改、分发，也欢迎基于本项目构建商用产品，只需在分发时保留原始版权声明。

---

## 致谢
感谢所有在闭源阶段提出意见的合作伙伴，也感谢即将加入开源社区的开发者们。每一次 Issue、每一次 PR、每一条评论，都是推动 Maduoduo 成长的力量。

---

## 附录：数据库表结构
<details>
<summary>点击展开查看完整的表结构列表</summary>

```text
cw_admin
字段名       数据类型    注释
id           int
root         tinyint     是否超级管理员 0-否 1-是
name         varchar     名称
avatar       varchar     用户头像
account      varchar     账号
password     varchar     密码
login_time   int         最后登录时间
login_ip     varchar     最后登录ip
multipoint_login tinyint 是否支持多处登录：1-是；0-否；
disable      tinyint     是否禁用：0-否；1-是；
create_time  int         创建时间
update_time  int         修改时间
delete_time  int         删除时间

cw_admin_dept
字段名       数据类型    注释
admin_id     int         管理员id
dept_id      int         部门id

cw_admin_jobs
字段名       数据类型    注释
admin_id     int         管理员id
jobs_id      int         岗位id

cw_admin_role
字段名       数据类型    注释
admin_id     int         管理员id
role_id      int         角色id

cw_admin_session
字段名       数据类型    注释
id           int
admin_id     int         用户id
terminal     tinyint     客户端类型：1-pc管理后台 2-mobile手机管理后台
token        varchar     令牌
update_time  int         更新时间
expire_time  int         到期时间

cw_article
字段名       数据类型    注释
id           int         文章id
cid          int         文章分类
title        varchar     文章标题
desc         varchar     简介
abstract     text        文章摘要
image        varchar     文章图片
author       varchar     作者
content      text        文章内容
click_virtual int        虚拟浏览量
click_actual int         实际浏览量
is_show      tinyint     是否显示:1-是.0-否
sort         int         排序
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_article_cate
字段名       数据类型    注释
id           int         文章分类id
name         varchar     分类名称
sort         int         排序
is_show      tinyint     是否显示:1-是;0-否
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_article_collect
字段名       数据类型    注释
id           int         主键
user_id      int         用户ID
article_id   int         文章ID
status       tinyint     收藏状态 0-未收藏 1-已收藏
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_background
字段名       数据类型    注释
id           int
url          varchar     图片
type         int         版型类型：1-竖版；2-横版
category_id  int         分类id
sort         int         排序
status       tinyint     状态:1-开启,0-关闭
media_id     varchar     媒体id
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_background_category
字段名       数据类型    注释
id           int
name         varchar     分类名称
sort         int         排序
status       tinyint     状态:1-开启,0-关闭
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_chat_category
字段名       数据类型    注释
id           int
name         varchar     类目名称
sort         int         排序
status       int         状态：1-开启，0-关闭
create_time  int         创建时间
image        varchar     图标
update_time  int         修改时间
delete_time  int         删除时间

cw_chat_record
字段名       数据类型    注释
id           int
user_id      int         用户ID
category_id  int         对话记录分类
other_id     int         创作id
ask          text        提问
reply        text        回复
type         int         类型：1-对话，2-创作
key          varchar     模型来源公司
model        varchar     对话模型
is_show      tinyint     是否显示：1-是；0-否；
censor_status tinyint    审核状态：0-未审核；1-合规；2-不合规；3-疑似；4-审核失败；
censor_result text       审核结果
censor_num   int         审核次数
extra        text        预留扩展字段
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_chat_record_category
字段名       数据类型    注释
id           int
user_id      int         用户id
name         varchar     对话分类名称
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_chat_record_collect
字段名       数据类型    注释
id           int
user_id      int         用户ID
records_id   int         对话记录ID
create_time  int         创建时间

cw_chat_sample
字段名       数据类型    注释
id           int
category_id  int         类目id
sort         int         排序
content      varchar     内容
status       tinyint     状态：1-是；0-否
create_time  int         创建时间
update_time  int         修改时间
delete_time  int         删除时间

cw_config
字段名       数据类型    注释
id           int
type         varchar     类型
name         varchar     名称
value        text        值
create_time  int         创建时间
update_time  int         更新时间

cw_creation_category
字段名       数据类型    注释
id           int
image        varchar     分类图标
name         varchar     类目名称
sort         int         排序
status       int         状态：1-开启，0-关闭
create_time  int         创建时间
update_time  int         修改时间
delete_time  int         删除时间

cw_creation_model
字段名       数据类型    注释
id           int
name         varchar     名称
image        varchar     图标
sort         int         排序
category_id  int         类别id
status       int         状态：1-开启，0-关闭
content      text        主题内容
tips         text        提示文字
create_time  int         创建时间
update_time  int         修改时间
delete_time  int         删除时间
context_num  int         上下文总数
top_p        decimal     随机属性
presence_penalty decimal 话题属性
frequency_penalty decimal 重复属性
n            int         最大回复
max_tokens   int         回复最大长度
temperature  decimal     词汇属性
form         text        表单

cw_creation_model_collect
字段名       数据类型    注释
id           int
creation_id  int         创作id
user_id      int         用户id
create_time  int         创建时间

cw_decals
字段名       数据类型    注释
id           int
url          varchar     图片
category_id  int         分类id
sort         int         排序
status       tinyint     状态:1-开启,0-关闭
media_id     varchar     媒体id
type         tinyint     类型：1-图片；2-动图
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间
source       tinyint     类型：1-阿里官方；2-后台上传

cw_decals_category
字段名       数据类型    注释
id           int
name         varchar     分类名称
sort         int         排序
status       tinyint     状态:1-开启,0-关闭
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_decorate_nav
字段名       数据类型    注释
id           int         主键
name         varchar     导航名称
icon         varchar     导航图标
link         varchar     链接地址
is_show      tinyint     是否显示
create_time  int         创建时间
update_time  int         更新时间

cw_decorate_page
字段名       数据类型    注释
id           int         主键
type         tinyint     页面类型 1=商城首页, 2=个人中心, 3=客服设置 4-PC首页
name         varchar     页面名称
data         text        页面数据
create_time  int         创建时间
update_time  int         更新时间

cw_decorate_tabbar
字段名       数据类型    注释
id           int         主键
name         varchar     导航名称
selected     varchar     未选图标
unselected   varchar     已选图标
link         varchar     链接地址
create_time  int         创建时间
update_time  int         更新时间

cw_dept
字段名       数据类型    注释
id           int         id
name         varchar     部门名称
pid          bigint      上级部门id
sort         int         排序
leader       varchar     负责人
mobile       varchar     联系电话
status       tinyint     部门状态（0停用 1正常）
create_time  int         创建时间
update_time  int         修改时间
delete_time  int         删除时间

cw_dev_crontab
字段名       数据类型    注释
id           int
name         varchar     定时任务名称
type         tinyint     类型 1-定时任务
system       tinyint     是否系统任务 0-否 1-是
remark       varchar     备注
command      varchar     命令内容
params       varchar     参数
status       tinyint     状态 1-运行 2-停止 3-错误
expression   varchar     运行规则
error        varchar     运行失败原因
last_time    int         最后执行时间
time         varchar     实时执行时长
max_time     varchar     最大执行时长
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_dev_pay_config
字段名       数据类型    注释
id           int
name         varchar     模版名称
pay_way      tinyint     支付方式:1-余额支付;2-微信支付;3-支付宝支付;
config       text        对应支付配置(json字符串)
icon         varchar     图标
sort         int         排序
remark       varchar     备注

cw_dev_pay_way
字段名       数据类型    注释
id           int
pay_config_id int        支付配置ID
scene        tinyint     场景:1-微信小程序;2-微信公众号;3-H5;4-PC;5-APP;
is_default   tinyint     是否默认支付:0-否;1-是;
status       tinyint     状态:0-关闭;1-开启;

cw_dict_data
字段名       数据类型    注释
id           int         id
name         varchar     数据名称
value        varchar     数据值
type_id      int         字典类型id
type_value   varchar     字典类型
sort         int         排序值
status       tinyint     状态 0-停用 1-正常
remark       varchar     备注
create_time  int         创建时间
update_time  int         修改时间
delete_time  int         删除时间

cw_dict_type
字段名       数据类型    注释
id           int         id
name         varchar     字典名称
type         varchar     字典类型名称
status       tinyint     状态 0-停用 1-正常
remark       varchar     备注
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_email_log
字段名       数据类型    注释
id           int         id
scene_id     int         场景id
email        varchar     邮箱
content      varchar     发送内容
code         varchar     验证码
is_verify    tinyint     是否已验证；0-否；1-是
check_num    int         验证次数
send_status  tinyint     发送状态：0-发送中；1-发送成功；2-发送失败
send_time    int         发送时间
results      text        发送结果
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_file
字段名       数据类型    注释
id           int         主键ID
cid          int         类目ID
source_id    int         上传者id
source       tinyint     来源类型[0-后台,1-用户]
type         tinyint     类型[10=图片, 20=视频]
name         varchar     文件名称
uri          varchar     文件路径
use_type     tinyint     使用类型：1-常规素材使用，2-数字人素材使用
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_file_cate
字段名       数据类型    注释
id           int         主键ID
pid          int         父级ID
type         tinyint     类型[10=图片，20=视频，30=文件，40=音频]
name         varchar     分类名称
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_generate_column
字段名       数据类型    注释
id           int         id
table_id     int         表id
column_name  varchar     字段名称
column_comment varchar   字段描述
column_type  varchar     字段类型
is_required  tinyint     是否必填 0-非必填 1-必填
is_pk        tinyint     是否为主键 0-不是 1-是
is_insert    tinyint     是否为插入字段 0-不是 1-是
is_update    tinyint     是否为更新字段 0-不是 1-是
is_lists     tinyint     是否为列表字段 0-不是 1-是
is_query     tinyint     是否为查询字段 0-不是 1-是
query_type   varchar     查询类型
view_type    varchar     显示类型
dict_type    varchar     字典类型
create_time  int         创建时间
update_time  int         修改时间

cw_generate_table
字段名       数据类型    注释
id           int         id
table_name   varchar     表名称
table_comment varchar    表描述
template_type tinyint    模板类型 0-单表(curd) 1-树表(curd)
author       varchar     作者
remark       varchar     备注
generate_type tinyint    生成方式 0-压缩包下载 1-生成到模块
module_name  varchar     模块名
class_dir    varchar     类目录名
class_comment varchar    类描述
admin_id     int         管理员id
menu         text        菜单配置
delete       text        删除配置
tree         text        树表配置
relations    text        关联配置
create_time  int         创建时间
update_time  int         修改时间

cw_hot_search
字段名       数据类型    注释
id           int         主键
name         varchar     关键词
sort         smallint    排序号
create_time  int         创建时间

cw_index_visit
字段名       数据类型    注释
id           int
ip           varchar     访客ip地址
terminal     tinyint     访问终端
visit        int         浏览量
create_time  int         访问时间
update_time  int
delete_time  int

cw_jobs
字段名       数据类型    注释
id           int         id
name         varchar     岗位名称
code         varchar     岗位编码
sort         int         显示顺序
status       tinyint     状态（0停用 1正常）
remark       varchar     备注
create_time  int         创建时间
update_time  int         修改时间
delete_time  int         删除时间

cw_key_down_rule
字段名       数据类型    注释
id           int
type         tinyint     规则类型：1-对话
ai_key       varchar     接口类型
rule         varchar     停用规则
prompt       varchar     停用提示
status       tinyint     状态：1-开启,0-关闭
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_key_pool
字段名       数据类型    注释
id           int         id
type         tinyint     类型
model        varchar     模型
key          varchar     密钥
status       tinyint     状态
remark       varchar     备注
appid        varchar     appid
secret       varchar     secret
notice       text        通知
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_knowledge
字段名       数据类型    注释
id           int         主键
user_id      int         用户ID
admin_id     int         后台ID
name         varchar     名称
image        varchar     图标
sort         smallint    排序
type         tinyint     集合类型: [1=问答型, 2=检索型]
is_enable    tinyint     是否启用: [0=否, 1=是]
create_type  tinyint     创建类型: [1=后台, 2=前台]
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_know_apply
字段名       数据类型    注释
id           int         主键
user_id      int         用户ID
admin_id     int         管理员ID
code         varchar     应用编号
kb_ids       varchar     知识库ID
category_id  int         分类ID
image        varchar     应用封面
name         varchar     应用名称
intro        varchar     应用简介
sort         smallint    排序编号
welcome      text        欢迎语
example      text        示例值
systemPrompt text        提示词
limitPrompt  text        限定词
searchEmptyText text     空搜索回复
searchSimilarity decimal 相似度
searchLimit  smallint    单次搜索数量
artificial_content text  人工客服内容
chat_icon    varchar     对话图标
auth_type    tinyint     使用权限: 0=所有用户, 1=指定分组
create_type  tinyint     创建类型: 1=后台创建, 2=前台创建
null_reply_type tinyint  空回复类型: [1=默认回复, 2=AI回复]
is_show_context tinyint  显示上下文: [0=否, 1=是]
is_show_quote tinyint    显示引用: [0=否, 1=是]
is_artificial tinyint    人工客服: [0=否, 1=是]
is_enable    tinyint     是否启用: [0=否, 1=是]
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_know_apply_auth
字段名       数据类型    注释
id           int         主键
apply_id     int         应用ID
group_id     int         分组ID

cw_know_apply_category
字段名       数据类型    注释
id           int         主键
name         varchar     分类名称
sort         smallint    排序编号
is_enable    tinyint     是否启用: [0=否, 1=是]
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_know_apply_collect
字段名       数据类型    注释
id           int         主键
user_id      int         用户ID
apply_id     int         应用ID
create_time  int         创建时间

cw_know_qa
字段名       数据类型    注释
id           int         主键
name         varchar     文件名称
content      text        文本内容
status       tinyint     拆分状态: [0=等待拆分, 1=拆分中, 2=拆分成功, 3=拆分失败]
error        text        错误信息
save_bk_ids  varchar     知识库ID
handle_id    int         处理人ID
handle_type  tinyint     处理类型: [1=后台, 2=用户]
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_know_qa_data
字段名       数据类型    注释
id           int         主键
qa_id        int         拆分ID
question     text        问题
answer       text        回复
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_know_record
字段名       数据类型    注释
id           int         主键
wind_id      int         窗口ID
user_id      int         用户ID
apply_id     int         应用ID
ask          text        提问
reply        text        答复
context      text        上下文
quotes       text        引用值
images       text        附带图片
files        text        附带文件
model        varchar     模型
identity     varchar     临时身份
channel_sn   varchar     渠道编号
channel_type tinyint     访问渠道: [0=前台, 1=网页, 2=API]
censor_status tinyint    审核状态: [0=未审核, 1=合规, 2=不合规, 3=疑似, 4=审核失败]
censor_num   smallint    审核次数
censor_result text       审核结果
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_know_release
字段名       数据类型    注释
id           int         主键
type         int         类型: [1=web, 2=api]
user_id      int         用户ID
apply_id     int         应用ID
apply_sn     varchar     应用编号
channel_sn   varchar     渠道编号
name         varchar     分享名称
secret       varchar     访问密钥
context_num  tinyint     上下文数
limit_total_chat int     限制每个用户累计总对话数
limit_today_chat int     限制每个用户每天总对话数
limit_exceed varchar     限制超出的默认回复
use_time     int         使用时间
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_know_window
字段名       数据类型    注释
id           int         主键
user_id      int         用户ID
apply_id     int         应用ID
name         varchar     窗口名称
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_material_upload_log
字段名       数据类型    注释
id           int
relation_id  int         关联表id
type         int         类型：1-音乐；2-背景图；3-前景图；4-贴纸
media_id     varchar     阿里媒体id
result       text        上传结果
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_music
字段名       数据类型    注释
id           int
category_id  int         分类id
cover        varchar     封面
url          varchar     音乐分类
name         varchar     名称
sort         int         排序
status       tinyint     状态:1-开启,0-关闭
media_id     varchar     媒体id
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_music_category
字段名       数据类型    注释
id           int
name         varchar     分类名称
sort         int         排序
status       tinyint     状态:1-开启,0-关闭
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_notice_record
字段名       数据类型    注释
id           int         ID
user_id      int         用户id
title        varchar     标题
content      text        内容
scene_id     int         场景
read         tinyint     已读状态;0-未读,1-已读
recipient    tinyint     通知接收对象类型;1-会员;2-商家;3-平台;4-游客(未注册用户)
send_type    tinyint     通知发送类型 1-系统通知 2-短信通知 3-微信模板 4-微信小程序
notice_type  tinyint     通知类型 1-业务通知 2-验证码
extra        varchar     其他
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_notice_setting
字段名       数据类型    注释
id           int
scene_id     int         场景id
scene_name   varchar     场景名称
scene_desc   varchar     场景描述
recipient    tinyint     接收者 1-用户 2-平台
type         tinyint     通知类型: 1-业务通知 2-验证码
system_notice text       系统通知设置
sms_notice   text        短信通知设置
oa_notice    text        公众号通知设置
mnp_notice   text        小程序通知设置
email_notice text        邮箱通知设置
support      char        支持的发送类型 1-系统通知 2-短信通知 3-微信模板消息 4-小程序提醒 5-邮箱通知
update_time  int         更新时间

cw_official_account_reply
字段名       数据类型    注释
id           int
name         varchar     规则名称
keyword      varchar     关键词
reply_type   tinyint     回复类型 1-关注回复 2-关键字回复 3-默认回复
matching_type tinyint    匹配方式：1-全匹配；2-模糊匹配
content_type tinyint     内容类型：1-文本
content      text        回复内容
status       tinyint     启动状态：1-启动；0-关闭
sort         int         排序
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_operation_log
字段名       数据类型    注释
id           int
admin_id     int         管理员ID
admin_name   varchar     管理员名称
account      varchar     管理员账号
action       varchar     操作名称
type         varchar     请求方式
url          varchar     访问链接
params       text        请求数据
result       text        请求结果
ip           varchar     ip地址
create_time  int         创建时间

cw_preposition
字段名       数据类型    注释
id           int
url          varchar     前置图
sort         int         排序
status       tinyint     状态:1-开启,0-关闭
media_id     varchar     媒体id
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_recharge_order
字段名       数据类型    注释
id           int         ID
sn           varchar     订单编号
user_id      int         用户ID
package_id   int         套餐ID
pay_sn       varchar     支付编号
pay_way      tinyint     支付方式: 2=微信支付, 3=支付宝支付
pay_status   tinyint     支付状态: 0=待支付, 1=已支付
refund_status tinyint    退款状态: 0=未退款, 1=已退款
order_amount decimal     订单金额
order_terminal tinyint   终端平台
transaction_id varchar   第三方平台交易流水号
chat_number  int         充值的对话数量
robot_number int         充值的机器人数量
kb_number    int         充值的知识库数量
video_duration int       充值的视频合成时长
snapshot     text        订单快照
pay_time     int         支付时间
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_recharge_package
字段名       数据类型    注释
id           int         主键
name         varchar     套餐名称
remarks      varchar     套餐描述
sell_price   decimal     销售价格
line_price   decimal     划线价格
chat_number  int         问答的数量
robot_number int         机器人数量
kb_number    int         知识库数量
video_duration int       视频合成时长
give_chat_number int     赠送问答的数量
give_robot_number int    赠送机器人数量
give_kb_number int       赠送知识库数量
give_video_duration int  赠送视频合成时长
sort         int         排序编号
status       tinyint     套餐状态: [0=关闭, 1=开启]
is_give      tinyint     赠送状态: [0=关闭, 1=开启]
is_recommend tinyint     是否推荐: [0=否, 1=是]
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_refund_log
字段名       数据类型    注释
id           int         id
sn           varchar     编号
record_id    int         退款记录id
user_id      int         关联用户
handle_id    int         处理人id
order_amount decimal     订单总的应付款金额
refund_amount decimal    本次退款金额
refund_status tinyint    退款状态，0退款中，1退款成功，2退款失败
refund_msg   text        退款信息
create_time  int         创建时间
update_time  int         更新时间

cw_refund_record
字段名       数据类型    注释
id           int         id
sn           varchar     退款编号
user_id      int         关联用户
order_id     int         来源订单id
order_sn     varchar     来源单号
order_type   varchar     订单来源 order-商品订单 recharge-充值订单
order_amount decimal     订单总的应付款金额
refund_amount decimal    本次退款金额
transaction_id varchar   第三方平台交易流水号
refund_way   tinyint     退款方式 1-线上退款 2-线下退款
refund_type  tinyint     退款类型 1-后台退款
refund_status tinyint    退款状态，0退款中，1退款成功，2退款失败
create_time  int         创建时间
update_time  int         更新时间

cw_sensitive_word
字段名       数据类型    注释
id           int
word         varchar     敏感词
status       tinyint     状态：1-开启，0-关闭
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_sms_log
字段名       数据类型    注释
id           int         id
scene_id     int         场景id
mobile       varchar     手机号码
content      varchar     发送内容
code         varchar     发送关键字
is_verify    tinyint     是否已验证；0-否；1-是
check_num    int         验证次数
send_status  tinyint     发送状态：0-发送中；1-发送成功；2-发送失败
send_time    int         发送时间
results      text        短信结果
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_system_menu
字段名       数据类型    注释
id           int         主键
pid          int         上级菜单
type         char        权限类型: M=目录，C=菜单，A=按钮
name         varchar     菜单名称
icon         varchar     菜单图标
sort         smallint    菜单排序
perms        varchar     权限标识
paths        varchar     路由地址
component    varchar     前端组件
selected     varchar     选中路径
params       varchar     路由参数
is_cache     tinyint     是否缓存: 0=否, 1=是
is_show      tinyint     是否显示: 0=否, 1=是
is_disable   tinyint     是否禁用: 0=否, 1=是
create_time  int         创建时间
update_time  int         更新时间

cw_system_role
字段名       数据类型    注释
id           int
name         varchar     名称
desc         varchar     描述
sort         int         排序
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_system_role_menu
字段名       数据类型    注释
role_id      int         角色ID
menu_id      int         菜单ID

cw_user
字段名       数据类型    注释
id           int         主键
group_ids    varchar     分组
sn           int         编号
avatar       varchar     头像
real_name    varchar     真实姓名
nickname     varchar     用户昵称
account      varchar     用户账号
password     varchar     用户密码
mobile       varchar     手机号码
email        varchar     邮箱
sex          tinyint     用户性别
channel      tinyint     注册渠道
is_disable   tinyint     是否禁用
login_ip     varchar     最后登录IP
login_time   int         最后登录时间
is_new_user  tinyint     是否是新注册用户
balance_chat int         对话余额
robot_num    int         可创建机器人的次数
kb_num       int         可创建知识库的次数
total_chat   int         累计提问次数
is_blacklist tinyint     是否在黑名单
video_duration decimal   视频可合成时长
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_user_account_log
字段名       数据类型    注释
id           int
sn           varchar     流水号
user_id      int         用户id
admin_id     int         管理ID
change_object tinyint    变动对象
change_type  smallint    变动类型
action       tinyint     动作 1-增加 2-减少
change_amount decimal    变动数量
left_amount  decimal     变动后数量
source_sn    varchar     关联单号
remark       varchar     备注
extra        text        预留扩展字段
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_user_auth
字段名       数据类型    注释
id           int
user_id      int         用户id
openid       varchar     微信openid
unionid      varchar     微信unionid
terminal     tinyint     客户端类型
create_time  int         创建时间
update_time  int         更新时间

cw_user_group
字段名       数据类型    注释
id           int         主键
name         varchar     名称
sort         smallint    排序
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_user_session
字段名       数据类型    注释
id           int
user_id      int         用户id
terminal     tinyint     客户端类型
token        varchar     令牌
update_time  int         更新时间
expire_time  int         到期时间

cw_video_generate_log
字段名       数据类型    注释
id           int
user_id      int         用户id
records_id   int         记录id
params       text        合成参数
status       tinyint     状态：1-合成中，2-合成成功，3-合成失败
type         tinyint     类型：1-数字人，2-视频点播
require_result text      请求合成结果
result       text        合成结果
long_time    int         视频时长:毫秒
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间

cw_video_records
字段名       数据类型    注释
id           int
user_id      int         用户id
name         varchar     视频标题
params       text        参数
avatar_params text       数字人参数
void_params  text        视频点播参数
void_type    tinyint     播报类型：1-文本；2-音频
status       tinyint     状态：1-草稿，2-合成中，3-合成成功，4-合成失败
sub_status   tinyint     子状态细分
video_url    varchar     视频地址
cover_picture_url varchar 视频封面图
result       text        合成结果
fail_reason  varchar     视频合成失败原因
long_time    varchar     视频时长（毫秒）
create_time  int         创建时间
update_time  int         更新时间
delete_time  int         删除时间
```

</details>
