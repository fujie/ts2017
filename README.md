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

#### 新規プロバイダとChannelの作成
まず、[LINE Developer Console](https://developers.line.me/)へアクセスします。  
以下の画面が表示されますので、
**「Start using LINE Login（LINEログインを始める）」**
をクリックし、LINE IDでログインします。  
（すみません、画面ショットは英語版ですが、日本語のブラウザでアクセスすれば日本語になります）  

![LINE Loginの利用を開始する](https://github.com/fujie/ts2017/blob/pic/line_start_line_login.png)

「＋」をクリックし、新しく追加するプロバイダ名を入力し、「追加」をクリックしてから「次のページ」をクリックして次ページへ遷移します。  

![新規プロバイダを作成する](https://github.com/fujie/ts2017/blob/pic/line_create_new_provider.png)

以下の情報を入力し、「確認」をクリックして次ページへ遷移します。

* アプリ名 : 任意のアプリケーション名
* アプリ説明 : 任意のアプリケーション説明
* アプリタイプ : WEBで使う、を選択
* メールアドレス : 任意のメールアドレス（お知らせが届くので管理者のアドレスがいいと思います）

![プロバイダ情報を入力する](https://github.com/fujie/ts2017/blob/pic/line_create_new_provider2.png)

確認ページでLINE Developer Agreementに同意してプロバイダとChannel（アプリ）の作成を完了します。  

#### 作成したChannelの基本設定
作成したChannelを開くとChannel IDとChannel Secretが取得できますので、メモしておきます。この値を後でAzure AD B2C上に設定を行うためです。

![チャネル設定を確認する](https://github.com/fujie/ts2017/blob/pic/line_channel_setting.png)


ひとまずLINE Login側の設定はこれで完了です。



