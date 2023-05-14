# allabout-ogawa-webhook
## 概要
SendGridのwebhookを触ったことがないので学習
実務でメール受信機能を実装する必要がありそうなので、実践で使う前に事前に学習しておくß


## 成果物
```
Github (README.md)
https://github.com/oootaiji/allabout-ogawa-webhook

アプリ (お金かかるため、現在停止中)
https://webhook.ogawa.allabout.oootaiji.com
```


## 目的・要件
### 目的
- 実践的な学習
    - 商用・業務で使うことを意識する
    - SendGridを使ってメールを受信し、それを自動返信・自動転送するまでの流れを掴むことが主軸
- devopsの学習
    - 本番環境はdockerコンテナが動いているが、ローカル開発環境もコンテナで動くようにする
    - devopsを意識して、開発環境と本番環境の連携を容易にする

### インフラ要件
- クラウド
    - GKE (Google Kubernetes Engine)
    - ロードバランサ (GKEのingress)
    - DNS (公開ドメイン)
    - SSL (証明書発行)
    - やらない
        - VPC (ネットワーク設計)
        - HPA (オートスケール)
- リポジトリ管理
    - Github
- CI/CD
    - Github Actions
    - デプロイのOSは、ubuntu20.04を使う

### アプリ要件
- 非機能要件
    - 最新Laravel (php8)
- 機能要件
    - メール送信機能
    - SendGridからのWebhook
        - メール自動転送機能
        - メール自動返信機能 (hello worldだけ返信)
        - 添付ファイルに対応は過剰なため、非対応にする


## 準備
### 必須環境
- OS
    - macOS
- ツール
    - docker
    - docker-compose
    - kubectl
    - gcloud

### 準備の流れ
1. 下準備
2. クラスターの作成
3. レジストリの作成
4. 公開用の固定IPの作成
5. サービスアカウント(権限)JSONの作成
6. Githubに環境変数を設定
7. メール送信用のDNSの設定
8. Inbound Parse Webhookの設定
9. メール受信用のDNSの設定


### 1. 下準備
- GCPの Kubernetes Engine APIを有効にしておく

    ```
    1. 課金を有効
    2. Artifact Registry and Google Kubernetes Engine API を有効
    ```

- ローカルでgcloudにログインしておく

    ```
    gcloud auth login
    ```

### 2. クラスターの作成
- kubernetesクラスタ作成

    ```
    cluster_name=allabout-ogawa-webhook
    gcloud container clusters create $cluster_name --machine-type=e2-micro --num-nodes=1 --zone=us-west1-a
    ```

- クラスタの認証情報を取得 (このコマンドで、以下で指定したクラスターにデプロイされるようになる)

    ```
    cluster_name=allabout-ogawa-webhook
    gcloud container clusters get-credentials $cluster_name
    ```

- クラスタ作成確認

    ```
    https://console.cloud.google.com/kubernetes/list/overview?project=o-taiji
    ```

### 3. レジストリの作成
- gcrの認証 (us-west1)
    ```
    gcloud auth configure-docker us-west1-docker.pkg.dev
    ```

- gcr作成

    ```
    repo_name=allabout-ogawa-webhook
    gcloud artifacts repositories create $repo_name --location=us-west1
    ```

- gcr確認 レジストリ確認

    ```
    https://console.cloud.google.com/artifacts/browse/o-taiji?project=o-taiji
    ```

### 4. 公開用の固定IPの作成
- 固定IP作成

    ```
    solid_ip_name=allabout-ogawa-webhook-ip
    gcloud compute addresses create $solid_ip_name --global
    ```

- 固定IP確認

    ```
    solid_ip_name=allabout-ogawa-webhook-ip
    gcloud compute addresses describe $solid_ip_name --global
    ```

- DNSの設定

    ```
    webhook.ogawa.allabout.oootaiji.comへ上記のIPをAレコードで追加
    ```

### 5. サービスアカウント(権限)JSONの作成
- IAMでサービスアカウントを作成
    - 以下の3つのロールを入れておく
        - Kubernetes Engine (とりあえず管理者でOK)
        - Artifact Registry (とりあえず管理者でOK)
        - ストレージ (とりあえず管理者でOK)

### 6. Githubに環境変数を設定
- config.yamlで使う環境変数を登録
    - Laravel本番環境用の.env
    - サービスアカウントJSON

### 7. メール送信のためのDNS設定
- SendGridのSender Authenticationを設定
    - チュートリアルどおりに設定する

### 8. Inbound Parse Webhookの設定
- webhookの設定
    - Receive Domain: mail.oootaiji.com
    - Check incoming emails for spam: `off`
    - POST the raw, full MIME message: `off`
    - URL: `https://webhook.ogawa.allabout.oootaiji.com/receive`

### 9. メール受信のためのDNS設定
- mxレコードを追加




## デプロイ
### 手順
1. ローカル開発環境構築
2. アプリ作成
3. gkeデプロイのmanifestファイル作成 
4. .github/workflows/workflow.yml作成
5. 準備実施
6. push

### 確認
- Web公開確認

    ```
    https://webhook.ogawa.allabout.oootaiji.com/
    ```


## メモ
### Inbound Parse Webhookの設定項目
- Check incoming emails for spam: `onにするとspamのreportやscoreをデータとして送ってくれる`
- POST the raw, full MIME message: `onにするとhederなどのデータもそのまま送ってくれる`


## 参考
- メール送信
    - [SendGrid PHP SDKの使用例](https://github.com/sendgrid/sendgrid-php/blob/main/USE_CASES.md)
- メール受信
    - [webhookの設定項目の参考にした](https://qiita.com/yaasita/items/a0747bb351dcd851aff6)
    - [Webhookのドキュメント](https://sendgrid.kke.co.jp/docs/API_Reference/Webhooks/parse.html)
    - [Inbound parse webhookの中身](https://docs.sendgrid.com/for-developers/parsing-email/setting-up-the-inbound-parse-webhook#default-parameters)