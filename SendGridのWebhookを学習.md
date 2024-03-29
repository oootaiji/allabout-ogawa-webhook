# SendGridのWebhookを学習
## 概要
知識は人に説明できる状態で初めて価値が出る
説明できる状態にするためアウトプットする
今回は、GCP環境でメール受信機能を実装するまでを目的とする

## GCP環境でメール受信機能
### メールサービスの選定
- 独自ドメインでメールを送受信するのであれば、メールサービスの利用は必須 (メールは古い技術で簡単と思われがちだが、自前でメールサーバー構築は難易度もコストも高い)
- SendGridかAWSのSESがよく使われている
- GCPはSendGridを推奨しているので、それを使う

### SendGridとは
- メール配信のクラウドサービス
- メールを自前で実装する上での課題を解決してくれるサービス


### メールの課題
- メールサーバーの実装
    - プロトコルの理解
- メールを確実に届ける
    - IPレピュテーション
        - 独自ドメイン
        - バウンスメール対策
        - 迷惑メール報告数がすくないか
    - IPウォームアップ


### メールのジレンマ
- メールはレガシー
- だけど必ず使う



### メールの仕組みを理解すると得られるもの

- 技術の成り立ち
    - 新しい技術がどうやって作られてきているのかがわかる
    - ハードと違ってすべてpcから作られているので、知識さえあればつくれることがわかる
    - 作り方を理解したら、その作られたもの規模がわかる
    - すごい技術だけど、時間をかければいけんじゃね？と思えてくる
    - ただ、時間はすごいかかるし、その作って間にもどんどん技術が進化していくので、自分とすごい人達の差がわかってくる
    - メールは複数のプロトコルから成り立っており、世の中たくさんの技術の上で成り立っていることがわかってくる
- 技術をつかって新しいプロダクトを考えるときに役立つ
    - スクラッチ開発とオープンソースを使っての開発の線引
    - 新規プロダクトの工数がみえてくる 
- 今ある当たり前のもののありがたみが分かる
    - 10Gbyte無料のGmailのありがたみと凄さ
    - 確実にメールがとどいている凄さ
- 技術は性善説のような形から始まって進歩していく
    - まずはセキュアを無視した形で始まっている事が多い
        - こうしたい、あれしたい、こうなったら便利、など。他人が悪用することは考えていない
        - 流行ったあとに、性善説はあてにならない。セキュリティを強化しよう。の流れになる
        - 例えば、http→https。メールだとあとからsmtp authが追加(RFC2554)
- そして新しい技術のプロダクトは、または金がほしい、こうすれば金が手に入るから進歩していく
    - 倫理観を無視したものだったり、詐欺まがいのものから始まる
        - こうすれば稼げるしか考えてない。他人のためとかは考えてない
        - 流行ったあとに、詐欺やギャンプルと違いなくて、社会問題になったから支持率稼ぎに法律化しよう。の流れになる。
        - かつてはLineもチャットの通信を暗号化していなかった。大々的にCMしていたのに、httpで通信していた
        - 他にも、コンプガチャ問題、ペニーオークション問題など
- 新しいプロトコルは、RFCやIETFを追っかけることが大事
    - 規格は世界で統一させないとインターネットは成り立たない。そこで国際規格であるRFCやIETFの進捗を確認する必要がある



### メールサービスのありがたみ
- Gmailを使えば無料でメールが使える
- SendGridが使えば自前のサービスにパーツとして使うことができる


### Webhookとは
リバースAPIとも呼ばれるもの
Web APIはこちらからAPIを叩くのに対して、Webhookは反対に向こうからAPIを叩かれる
そのため、こちら側は叩かれるAPIを用意する必要がある
向こう側のサービスがイベントを検知したらリアルタイムで、こちら側のアプリへ通知してくれるメリットがある

## SendGridについて
### 欲しい機能
- Web API `Freeプランである`
- 独自ドメイン `Freeプランである`
- ログ管理 `Freeプランである`
- バウンスメールの管理 `Freeプランである`
- メール受信のWebhook `Freeプランである`
- ドキュメント `ある`
- 複数事業への対応 (サブアカウントのような機能) `Proのみ`
- 複数部署への対応 (権限の管理のような機能) `Freeプランであるが2つまで`

→ 複数サービスを扱う場合は、Proプランがあったほうが良い



## SendGridをつかってメール送信
### 手順


## SendGridをつかってメール受信
### 手順





## 参考文献
- [SendGridのドキュメント](https://sendgrid.kke.co.jp/docs/)
- [SendGridの料金プランと機能](https://sendgrid.kke.co.jp/plan/)
- [SMTPのwiki](https://ja.wikipedia.org/wiki/Simple_Mail_Transfer_Protocol)
- [SMTPの規格の歴史がわかるやつ](https://atmarkit.itmedia.co.jp/ait/articles/0105/02/news001.html)
- [メールの規格の歴史がわかるやつ](https://sendgrid.kke.co.jp/blog/?p=10300)
- [Webhookとは](https://sendgrid.kke.co.jp/blog/?p=1851)
- [IPレピュテーション](https://sendgrid.kke.co.jp/blog/?p=14407)
- [IPレピュテーション2](https://qiita.com/nfujita55a/items/5848fcfbbe6cbf7d98c3)
- [SendGridの特徴](https://sendgrid.kke.co.jp/about/)
- [SenderIdとは](https://sendgrid.kke.co.jp/blog/?p=2292)
- [SPF,DKIMの特徴](https://sendgrid.kke.co.jp/blog/?p=10121)
- [メールBoxに届くまで](https://sendgrid.kke.co.jp/blog/?p=4092)