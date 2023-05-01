# GithubActionsの学習
## 概要
知識は人に説明できる状態で初めて価値が出る
説明できる状態にするためアウトプットする
主に、CircleCIとの違いから学習

## CircleCIとは
### CircleCIとは
一言でいうと、Saas型のCI/CDサービス
CI/CDサービスのやることは主に以下の３つである

- ビルド
- テスト
- デプロイ

### CIとは
- Continuous Integrationの略
- コードが常に問題ないかを確認するテストの部分
- コードのテストを自動化

### CDとは
- Continuous Deliveryの略
- ビルドとデプロイの部分で、ビルドしたものを本番環境へ配信するまでを自動化する機能
- コードのデプロイを自動化

### CI/CDとは
- CI + CDのこと


## CircleCIとGithubActionsの違い

- Github Actionsのほうがシンプルな記述
- ワークフローをまたいで保存 (cacheへの保存)
- ジョブをまたいで保存 (artifactへ保存)


## 参考文献

- [Github ActionsのQuick Start](https://docs.github.com/ja/actions/quickstart)