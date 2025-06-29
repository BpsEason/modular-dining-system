# Modular Dining System

一款專為餐飲業打造的企業級 SaaS 平台，結合**多租戶架構**、**AI 推薦引擎**與**高效運營管理**，實現流暢的客戶體驗與業務自動化。基於 **Laravel**、**FastAPI** 和 **Vue.js** 構建，採用 **Docker** 容器化與 **GitHub Actions** 自動化 CI/CD，確保穩定性、可擴展性與快速部署，助力餐廳數位轉型。

## 核心優勢
- **多租戶隔離**：透過 `X-Tenant-ID` 實現資料分離，確保各餐廳數據獨立，兼顧安全與高效。
- **AI 驅動推薦**：FastAPI 整合協同過濾算法（KNN），基於用戶歷史數據提供精準菜單推薦，提升訂單轉化率。
- **模組化架構**：Laravel 模組化設計（`nwidart/laravel-modules`），涵蓋客戶管理、訂單處理與行銷，易於擴展。
- **細粒度權限**：採用 `spatie/laravel-permission`，實現 RBAC 角色控制，滿足多層級用戶需求。
- **可靠通知系統**：推播通知支援重試機制（`tries=3`）與錯誤日誌（`NotificationLog`），確保訊息穩定傳遞。
- **現代化前端**：Vue.js 3 搭配 Pinia 與 Tailwind CSS，打造直觀、響應式儀表板體驗。
- **高效部署**：Docker 容器化與 GitHub Actions，實現環境一致性與零停機更新。

## 技術架構
- **後端**：
  - Laravel 10 (PHP 8.2)：核心 API，支援模組化與高效業務邏輯
  - FastAPI (Python 3.11)：高效推薦引擎，整合機器學習
- **前端**：Vue.js 3，搭配 Pinia、Vue Router 與 Tailwind CSS
- **資料庫**：MySQL 8.0，高效持久化儲存
- **快取與隊列**：Redis，支援快速存取與非同步任務
- **容器化**：Docker 與 Docker Compose
- **CI/CD**：GitHub Actions，含測試與覆蓋率報告
- **API 文件**：OpenAPI 3.0 (Swagger)

## 系統架構圖
展示核心模組與資料流互動：

```mermaid
graph TB
  subgraph 使用者端
    BROWSER[前端瀏覽器 - Vue]
  end

  subgraph API 通訊層
    AXIOS[Axios 請求攔截器]
  end

  subgraph 後端服務層
    LARAVEL[Laravel API - 模組化 / RBAC / 多租戶]
    FASTAPI[FastAPI 推薦服務]
  end

  subgraph 資料儲存層
    MYSQL[MySQL 資料庫]
    REDIS[Redis 快取]
  end

  BROWSER --> AXIOS
  AXIOS --> LARAVEL
  LARAVEL -->|推薦請求| FASTAPI
  LARAVEL --> MYSQL
  LARAVEL --> REDIS
  FASTAPI --> REDIS
  FASTAPI --> MYSQL
  LARAVEL -->|JSON 回應| AXIOS
  AXIOS -->|畫面更新| BROWSER

```

### 📖 架構說明

本系統採用前後端分離、多服務整合的微服務架構：

- **Vue 前端** 提供響應式儀表板，透過 Axios 傳遞 JWT 與 `X-Tenant-ID` 請求頭，呼叫 Laravel API。
- **Laravel** 為核心 API 服務，負責權限控管（RBAC）、模組化業務邏輯（如客戶、訂單、通知）、多租戶隔離與資料一致性驗證。
- **FastAPI 推薦引擎** 作為獨立微服務，透過協同過濾模型（KNN）與 Redis 快取回傳個性化推薦結果。
- **資料存儲層** 採用 MySQL（儲存業務資料）與 Redis（快取與佇列），達到高效處理與容錯能力。
- **通訊流程** 為單向請求、雙向回應設計，從 Vue → API Gateway（Axios）→ Laravel → FastAPI → 資料層，再由 Laravel 回傳結果至前端。

整體架構強調模組解耦、彈性擴充、效能優先與開發維運友善，適用於中大型連鎖餐飲品牌的 SaaS 平台場景。

## 環境需求
- **Docker** 與 **Docker Compose** (v2.0+)
- **Node.js** (v18.x)
- **PHP** (8.2) 與 **Composer**
- **Python** (3.11)
- **Git**

## 快速部署
**注意**：倉庫僅包含核心程式碼，需自行安裝依賴並配置環境。

1. **複製專案**：
   ```bash
   git clone https://github.com/BpsEason/modular-dining-system.git
   cd modular-dining-system
   ```

2. **安裝依賴**：
   - Laravel：
     ```bash
     cd laravel-app
     composer install
     cp .env.example .env
     php artisan key:generate
     ```
   - FastAPI：
     ```bash
     cd fastapi-service
     pip install -r requirements.txt
     ```
   - Vue.js：
     ```bash
     cd vue-frontend
     npm install
     ```

3. **啟動 Docker 服務**：
   ```bash
   docker-compose up --build -d
   ```
   啟動服務：
   - Laravel API：`http://localhost/api/v1`
   - FastAPI：`http://localhost:8000/api/v1`
   - Vue 前端：`http://localhost:5173`
   - MySQL：`port 3306`
   - Redis：`port 6379`

4. **初始化資料庫**：
   ```bash
   docker-compose exec php-fpm php artisan migrate --seed
   ```

5. **存取應用**：
   - 前端：`http://localhost:5173`
   - 測試帳號：
     - 管理員：`admin@example.com` / `password`
     - 經理：`manager@example.com` / `password`
     - 員工：`staff@example.com` / `password`

## 專案結構
```
modular-dining-system/
├── laravel-app/                  # Laravel 後端
│   ├── app/                      # 模型、中間件、佇列
│   ├── modules/                  # 模組（客戶、訂單、行銷）
│   ├── database/                 # 遷移與種子資料
│   ├── routes/                   # API 路由
│   └── Dockerfile
├── fastapi-service/              # FastAPI 推薦服務
│   ├── main.py
│   ├── requirements.txt
│   └── Dockerfile
├── vue-frontend/                 # Vue.js 前端
│   ├── src/                      # 元件、視圖、路由
│   ├── tests/                    # Vitest 測試
│   ├── tailwind.config.js
│   └── Dockerfile
├── docs/                         # API 文件
│   └── api/openapi.yaml
├── docker/                       # Nginx 設定
├── .github/workflows/            # CI/CD 設定
├── docker-compose.yml
└── create_project.sh
```

## API 文件
採用 **OpenAPI 3.0** 規範，核心端點：
- `/api/v1/login`：用戶認證，獲取 Bearer Token
- `/api/v1/customers`：客戶管理（需 `customer.read`、`customer.create`）
- `/api/v1/orders`：訂單管理（需 `order.read`、`order.create`）
- `/api/v1/notifications/send`：推播通知（需 `notification.send`）
- `/api/v1/recommendations/{customerId}`：AI 推薦（FastAPI）

詳細規格：`docs/api/openapi.yaml`。

## 常見問題與解答

### 架構設計

**Q1: 為什麼選擇 Laravel + FastAPI 的組合，而不是單純使用 Laravel？**

**A**: Laravel 負責核心業務邏輯（如 CRUD、RBAC、多租戶），提供穩定的 API 與模組化架構；FastAPI 專注於高效能的 AI 推薦引擎，憑藉異步特性與 Python 生態，提供低延遲與高吞吐量。  
- **優勢**：分工明確，FastAPI 適合機器學習任務，Laravel 擅長業務邏輯。  
- **挑戰**：跨服務通訊增加網路延遲，需優化 API 設計與認證一致性。

---

**Q2: 如何確保多租戶資料隔離與安全？X-Tenant-ID 的作用是什麼？**

**A**: 採用 **Shared database, shared schema** 模式，透過 `tenant_id` 欄位隔離資料。`X-Tenant-ID` 標頭在每次 API 請求中標識租戶，由 `CheckTenant` 中間件驗證，確保請求僅存取對應租戶資料。

**程式碼範例**：
```php
// laravel-app/app/Http/Middleware/CheckTenant.php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        // 取得 X-Tenant-ID 標頭
        $tenantId = $request->header('X-Tenant-ID');
        // 驗證標頭與用戶的 tenant_id 是否匹配
        if (!$tenantId || !auth()->user()->tenant_id || (int) $tenantId !== auth()->user()->tenant_id) {
            Log::warning("無效的租戶請求: Tenant ID {$tenantId}");
            return response()->json(['message' => 'Unauthorized or invalid tenant.'], 403);
        }
        return $next($request);
    }
}
```

---

**Q3: 模組化架構的優勢是什麼？與單體架構相比如何？**

**A**: 使用 `nwidart/laravel-modules` 將功能分為獨立模組（如 `CustomerProfile`、`PosCore`），提高程式碼組織性。  
- **優勢**：易於維護、可擴展、利於團隊協作。  
- **與單體架構比較**：模組化便於功能拆分與迭代，但初期配置較複雜。

**程式碼範例**：
```php
// laravel-app/modules/CustomerProfile/Routes/api.php
<?php
use Illuminate\Support\Facades\Route;
use Modules\CustomerProfile\Http\Controllers\CustomerController;

Route::prefix('customers')->group(function() {
    // 客戶管理路由，綁定權限中間件
    Route::get('/', [CustomerController::class, 'index'])->name('customers.index')->middleware('permission:customer.read');
    Route::post('/', [CustomerController::class, 'store'])->name('customers.store')->middleware('permission:customer.create');
});
```

---

### 效能與優化

**Q4: Redis 的應用場景是什麼？若宕機會有什麼影響？**

**A**:  
- **快取**：緩存顧客資料等高頻數據，減少資料庫負載。  
- **佇列**：處理非同步任務，如推播通知。  
- **宕機影響**：快取失效時回退至 MySQL，效能下降；佇列任務失敗，但透過重試（`tries=3`）與 `NotificationLog` 日誌確保可追溯。

**程式碼範例**：
```php
// laravel-app/app/Jobs/SendPushNotification.php
<?php
namespace App\Jobs;

use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendPushNotification implements ShouldQueue
{
    use Queueable;

    public $tries = 3; // 最多重試 3 次
    public $timeout = 30; // 任務超時 30 秒

    public function __construct(protected string $message, protected int $userId) {}

    public function handle(): void
    {
        try {
            // 發送推播通知
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('PUSH_SERVICE_API_KEY'),
            ])->post(env('PUSH_SERVICE_ENDPOINT'), [
                'user_id' => $this->userId,
                'message' => $this->message,
            ]);

            if (!$response->successful()) {
                throw new \Exception("推播失敗: {$response->status()}");
            }
            Log::info("推播成功: user {$this->userId}");
        } catch (Throwable $e) {
            // 記錄錯誤至資料庫
            NotificationLog::create([
                'user_id' => $this->userId,
                'message' => $this->message,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            throw $e; // 觸發重試
        }
    }
}
```

---

**Q5: 如何確保 API 穩定性與效能？**

**A**:  
- **快取**：Redis 緩存熱點數據。  
- **非同步**：耗時任務透過 Redis 佇列處理。  
- **資料庫**：外鍵約束與索引優化查詢。  
- **監控**：`LogApiRequest` 中間件記錄請求日誌。

**程式碼範例**：
```php
// laravel-app/app/Http/Middleware/LogApiRequest.php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        // 記錄 Tenant ID
        $tenantId = $request->header('X-Tenant-ID');
        if ($tenantId) {
            Log::info("收到來自 Tenant ID: {$tenantId} 的請求");
        }
        return $next($request);
    }
}
```

---

### CI/CD 與部署

**Q6: CI/CD 流程如何運作？推送到 main 分支會發生什麼？**

**A**: GitHub Actions 實現全自動 CI/CD：  
1. **觸發**：推送至 `main` 或 Pull Request 啟動 Workflow。  
2. **測試**：執行 PHPUnit（Laravel）、Vitest（Vue）、Pytest（FastAPI），生成覆蓋率報告並上傳至 Codecov。  
3. **部署**：測試通過後，建置並推送 Docker 映像至 Docker Hub。

**程式碼範例**：
```yaml
# .github/workflows/ci.yml
name: CI and Deploy
on:
  push:
    branches: [main]
  pull_request:
    branches: [main]
jobs:
  laravel-tests:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root_password
          MYSQL_DATABASE: modular_dining_system
        ports:
          - 3306:3306
      redis:
        image: redis:alpine
        ports:
          - 6379:6379
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install PHP Dependencies
        run: cd laravel-app && composer install --no-dev
      - name: Run Laravel Tests
        run: cd laravel-app && ./vendor/bin/phpunit --coverage-clover coverage.xml
      - name: Upload Coverage
        uses: codecov/codecov-action@v3
        with:
          files: ./laravel-app/coverage.xml
```

---

**Q7: 為什麼選擇 Docker 部署？**

**A**:  
- **一致性**：統一開發與生產環境，解決環境差異問題。  
- **快速部署**：`docker-compose up` 一鍵啟動服務。  
- **隔離性**：容器化服務降低衝突風險。

**程式碼範例**：
```yaml
# docker-compose.yml
version: '3.8'
services:
  php-fpm:
    build:
      context: ./laravel-app
      dockerfile: Dockerfile
    environment:
      - DB_HOST=mysql
      - REDIS_HOST=redis
  mysql:
    image: mysql:8.0
    environment:
      - MYSQL_ROOT_PASSWORD=root_password
  redis:
    image: redis:alpine
  fastapi:
    build:
      context: ./fastapi-service
      dockerfile: Dockerfile
  vue-frontend:
    build:
      context: ./vue-frontend
      dockerfile: Dockerfile
```

---

### 程式碼細節

**Q8: Laravel 測試如何設計？如何測試權限？**

**A**: 使用 PHPUnit 測試 API 與權限邏輯，模擬不同角色用戶，驗證 403 錯誤等。

**程式碼範例**：
```php
// laravel-app/tests/Feature/CustomerControllerTest.php
<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized_user_cannot_create_customer()
    {
        // 模擬 Staff 角色用戶
        $staffUser = User::factory()->create();
        $staffUser->assignRole(Role::create(['name' => 'staff']));
        // 發送無權限請求
        $response = $this->actingAs($staffUser)->postJson('/api/v1/customers', [
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'tenant_id' => 1,
        ]);
        // 驗證 403 錯誤
        $response->assertStatus(403);
        $response->assertJson(['message' => 'This action is unauthorized.']);
    }
}
```

---

**Q9: Vue.js 如何處理狀態管理與 API 呼叫？**

**A**: 使用 **Pinia** 管理狀態，**Axios** 處理 API 呼叫，封裝於 actions 中，簡化元件邏輯。

**程式碼範例**：
```javascript
// vue-frontend/src/store/auth.js
import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: null,
    tenantId: '1',
    user: null,
  }),
  actions: {
    async login(email, password) {
      try {
        // 發送登入請求
        const response = await axios.post('http://localhost/api/v1/login', { email, password });
        this.token = response.data.token;
        this.user = response.data.user;
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        axios.defaults.headers.common['X-Tenant-ID'] = this.tenantId;
        return true;
      } catch (error) {
        console.error('Login failed:', error);
        return false;
      }
    },
  },
  persist: true,
});
```

---

**Q10: FastAPI 推薦引擎如何運作？其他推薦策略有哪些？**

**A**: FastAPI 作為微服務，使用 KNN 算法基於歷史數據生成推薦。  
- **運作**：接收顧客 ID，查詢 Redis/MySQL 數據，計算相似性並返回推薦清單。  
- **其他策略**：RFM 分析（分群高價值顧客）、流行度推薦（熱銷品項）、內容型過濾（基於餐點屬性）。

**程式碼範例**：
```python
# fastapi-service/main.py
from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
from typing import List
import pandas as pd
from sklearn.neighbors import NearestNeighbors

app = FastAPI()

# 模擬數據
MOCK_USER_HISTORY = {101: [1, 2, 5], 102: [3, 4, 6]}
MOCK_MENU_ITEMS = {1: {"name": "Burger"}, 2: {"name": "Fries"}}

# 建構 KNN 模型
def get_recommendations_model():
    all_items = list(MOCK_MENU_ITEMS.keys())
    user_item_matrix = pd.DataFrame(0, index=list(MOCK_USER_HISTORY.keys()), columns=all_items)
    for user_id, items in MOCK_USER_HISTORY.items():
        user_item_matrix.loc[user_id, items] = 1
    model = NearestNeighbors(metric='cosine', algorithm='brute')
    model.fit(user_item_matrix)
    return model, user_item_matrix

RECOMMENDATION_MODEL, USER_ITEM_MATRIX = get_recommendations_model()

class RecommendationItem(BaseModel):
    id: int
    name: str
    score: float = 1.0

class RecommendationResponse(BaseModel):
    items: List[RecommendationItem]
    strategy: str

@app.get("/api/v1/recommendations/{customer_id}", response_model=RecommendationResponse)
def get_recommendations(customer_id: int):
    if customer_id not in MOCK_USER_HISTORY:
        raise HTTPException(status_code=404, detail="Customer not found")
    user_history_vector = USER_ITEM_MATRIX.loc[[customer_id]].values
    distances, indices = RECOMMENDATION_MODEL.kneighbors(user_history_vector, n_neighbors=len(MOCK_MENU_ITEMS))
    recommended_items = [
        RecommendationItem(
            id=USER_ITEM_MATRIX.columns[item_index],
            name=MOCK_MENU_ITEMS[USER_ITEM_MATRIX.columns[item_index]]['name'],
            score=1 - distances[0][indices[0].tolist().index(item_index)]
        )
        for item_index in indices[0]
        if USER_ITEM_MATRIX.columns[item_index] in MOCK_MENU_ITEMS
    ]
    return RecommendationResponse(items=recommended_items, strategy="Collaborative Filtering (KNN)")
```

## 測試與品質保證
- **Laravel**：PHPUnit 測試 API 與權限邏輯。  
  ```bash
  docker-compose exec php-fpm ./vendor/bin/phpunit
  ```
- **Vue.js**：Vitest 測試元件與覆蓋率。  
  ```bash
  docker-compose exec vue-frontend npm run test
  ```
- **FastAPI**：Pytest 驗證推薦邏輯。  
  ```bash
  docker-compose exec fastapi pytest
  ```

## 企業級 CI/CD 流程
GitHub Actions 實現高效 DevOps：  
- **自動測試**：涵蓋 Laravel、Vue、FastAPI，生成覆蓋率報告並上傳 Codecov。  
- **無縫部署**：推送至 `main` 分支時，建置 Docker 映像並推送至 Docker Hub。  
- **一致性**：Docker 確保環境統一。  
配置：`.github/workflows/ci.yml`。

## GitHub 倉庫
原始碼：  
[https://github.com/BpsEason/modular-dining-system.git](https://github.com/BpsEason/modular-dining-system.git)

## 貢獻與聯繫
1. Fork 專案。
2. 建立分支（`git checkout -b feature/YourFeature`）。
3. 提交變更（`git commit -m "Add YourFeature"`）。
4. 推送分支（`git push origin feature/YourFeature`）。
5. 開啟 Pull Request。  
問題反饋請於 GitHub 提交 Issue。
