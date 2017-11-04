# Azure AD B2C + LINE連携 / チュートリアル
## はじめに
Tech Summit 2017 で使用したデモ環境を作成する為のチュートリアルです。  
以下の注意事項です。

* 試用（ポリシー内から呼び出しているLINE連携モジュールは2017年12月末で削除予定です）
* 動作・性能等の保証はありません。
* 商談を含むお問い合わせは [こちら](<mailto:b2cidm@ctc-g.co.jp>) まで

## Tech Summit 2017 セッション情報

* セッションID : SEC007
* セッションタイトル : Azure AD B2C ＋ LINE 学校や企業における次世代 ID/ メッセージ基盤
* 担当 : 伊藤忠テクノソリューションズ株式会社 西日本システム技術第2部 富士榮 尚寛

## 環境構築手順
### 準備物
あらかじめ以下を準備しておいてください。

* LINE ID（ブラウザでログインする必要があるので、メールアドレスおよびパスワードを設定しておく必要があります）
* Azureのサブスクリプション（Azure AD B2Cテナントを作成・紐づけるサブスクリプションが必要です。トライアル版で構いません）

### LINE Login
LINE Loginの利用を開始します。  

まず、[LINE Developer Console](https://developers.line.me/ja/) へアクセスします。  
以下の画面が表示されますので、**「Start using LINE Login（LINEログインを始める）」**をクリックし、LINE IDでログインします。  

[![LINE Loginの利用を開始する](https://github.com/fujie/ts2017/blob/pic/line_start_line_login.png)]

