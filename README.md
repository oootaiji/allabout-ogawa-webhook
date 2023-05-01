# allabout-ogawa-githubactions
## 概要
Github Actinosを触ったことがないので学習
実務でCircleCIからGithub Actionsへ移行があるということで、移行を意識してGithub ActionsでGKEのデプロイを実施する


## 成果物
```
Github (README.md)
https://github.com/oootaiji/allabout-ogawa-githubactions

アプリ (お金かかるため、現在停止中)
https://githubactions.ogawa.allabout.oootaiji.com
```


## 目的・要件
### 目的
- 実践的学習
    - 商用・業務で使うことを意識する
- devopsの学習
    - 本番環境はdockerコンテナが動いているが、ローカル開発環境もコンテナで動くようにする
    - devopsを意識して、開発環境と本番環境の連携を容易にする
- Github Actionsの学習が主軸

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
- 最新Laravel (php8)
- hello worldだけ出力


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
1. GKEの準備
2. クラスターの作成
3. レジストリの作成
4. 公開用の固定IPの作成
5. サービスアカウント(権限)JSONの作成
6. Githubに環境変数を設定

### 1. GKEの準備
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
    cluster_name=allabout-ogawa-githubactions
    gcloud container clusters create $cluster_name --machine-type=e2-micro --num-nodes=1 --region=us-west1
    ```

- クラスタの認証情報を取得 (このコマンドで、以下で指定したクラスターにデプロイされるようになる)

    ```
    cluster_name=allabout-ogawa-githubactions
    gcloud container clusters get-credentials $cluster_name
    ```

- クラスタ作成確認

    ```
    https://console.cloud.google.com/kubernetes/list/overview?project=o-taiji
    ```

### 3. レジストリの作成
- gcrの認証 (us-east1)
    ```
    gcloud auth configure-docker us-east1-docker.pkg.dev
    ```

- gcr作成

    ```
    repo_name=allabout-ogawa-githubactions
    gcloud artifacts repositories create $repo_name --repository-format=docker --location=us-east1
    ```

- gcr確認 レジストリ確認

    ```
    https://console.cloud.google.com/artifacts/browse/o-taiji?project=o-taiji
    ```

### 4. 公開用の固定IPの作成
- 固定IP作成

    ```
    solid_ip_name=allabout-ogawa-githubactions-ip
    gcloud compute addresses create $solid_ip_name --global
    ```

- 固定IP確認

    ```
    solid_ip_name=allabout-ogawa-githubactions-ip
    gcloud compute addresses describe $solid_ip_name --global
    ```

- DNSの設定

    ```
    githubactions.ogawa.allabout.oootaiji.comへ上記のIPをAレコードで追加
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
    https://githubactions.ogawa.allabout.oootaiji.com/
    ```


## 参考
- [Github Actions](https://docs.github.com/ja/actions)
- [Workflowの書き方一覧](https://docs.github.com/ja/actions/using-workflows/workflow-syntax-for-github-actions)
- [CircleCIからGithub Actionsへ移行](https://docs.github.com/ja/actions/migrating-to-github-actions/migrating-from-circleci-to-github-actions)
- [Composite Action](https://zenn.dev/tmrekk/articles/5fef57be891040)
- [compsiteで環境変数](https://zenn.dev/noraworld/articles/github-actions-env-bug)
- [jsonを使ったgkeの認証](https://githubactions.ogawa.allabout.oootaiji.com/)