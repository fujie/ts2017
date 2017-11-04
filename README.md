# Azure AD B2C + LINE連携 / チュートリアル
## はじめに
Tech Summit 2017 で使用したデモ環境を作成する為のチュートリアルです。  
以下、注意事項です。

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

### LINE Loginの設定
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

### Azure AD B2Cの設定
続いてAzure AD B2Cの設定を行います。  

#### Azure AD B2Cディレクトリの作成
Azureポータルで「B2C」で検索をし、新規にAzure AD B2Cのリソースを作成します。  
（「＋新規」をクリックし、検索窓に「B2C」と入れると「Azure Active Directory B2C」が候補に出てくるはずです）  

![Azure AD B2Cを作成する](https://github.com/fujie/ts2017/blob/pic/aadb2c_create.png)

以下の情報を入力して「作成」をクリックしてディレクトリを作成します。  

* 組織名 : 任意の組織名
* ドメイン名 : 任意のドメイン名（ユニークな名前が必要）
* 国/地域 : まだ日本は選べません

ここで設定したドメイン名をメモしておきます。LINEやB2Cポリシーなど複数個所へ設定を行うためです。  

![Azure AD B2Cを作成する](https://github.com/fujie/ts2017/blob/pic/aadb2c_create2.png)


作成が完了した後、以下の画面が出てきた場合はAzureサブスクリプションへディレクトリの紐づけが行えていないので、紐づけを行います。（この表示にならなかった方はスキップしてもらって大丈夫です）  

![Azure AD B2Cを作成する](https://github.com/fujie/ts2017/blob/pic/aadb2c_create3.png)

（サブスクリプション紐づけが必要な場合のみ）  
再度、Azureポータルより新規にAzure AD B2Cリソースの作成を行います。  
![Azure AD B2Cを作成する](https://github.com/fujie/ts2017/blob/pic/aadb2c_create.png)

今度は「既存のAzure AD B2Cディレクトリをサブスクリプションへ紐づける」を選択し、先ほど作成したディレクトリを選択し、以下の設定を行います。

* Azure AD B2Cテナント : 先ほど作成したAzure AD B2Cディレクトリ
* Azure AD B2Cリソース名 : 同上
* サブスクリプション : 紐付け対象とするAzureサブスクリプション
* リソースグループ : 任意のリソースグループ
* リソースグループの地域 : 任意の国/地域

![Azure AD B2Cを作成する](https://github.com/fujie/ts2017/blob/pic/aadb2c_create4.png)


（ここから共通の手順）  
上手くディレクトリが作成できるとAzure AD B2Cの管理ブレードが表示されますので、「Identity Experience Framework」をクリックし、カスタムポリシーなどを設定していきます。

![Azure AD B2C管理ブレード](https://github.com/fujie/ts2017/blob/pic/aadb2c_blade.png)

#### 鍵の作成
ここでは、Azure AD B2C自体が使う2つの鍵の生成と、LINE Loginへアクセスする為のClient IDをAzure AD B2Cに登録します。

Azure AD B2C自体が使う鍵

* RP（Relying Party）に対して発行するJWT（JSON Web Token）に署名をするための鍵
* RPに対して発行するJWTを暗号化するための鍵

LINE Loginで利用する鍵

* LINE LoginのClient Secret

鍵は、IEF（Identity Experience Framework）の管理Bladeで登録・管理します。

![鍵の登録](https://github.com/fujie/ts2017/blob/pic/aadb2c_createkey.png)

まずは、Azure AD B2C自体が使う鍵です。以下の通り作成してください。  

＜署名するための鍵＞

* 作成オプション : Generate
* 名称 : TokenSigningKeyContainer
* タイプ : RSA
* 用途 : Signature

![署名鍵の登録](https://github.com/fujie/ts2017/blob/pic/aadb2c_createkey2.png)

＜暗号化するための鍵＞
* 作成オプション : Generate
* 名称 : TokenEncritionKeyContainer
* タイプ : RSA
* 用途 : Encryption

![暗号鍵の登録](https://github.com/fujie/ts2017/blob/pic/aadb2c_createkey3.png)


次に、LINE LoginのClient Secretを登録します。

* 作成オプション : Manual
* 名称 : LINEOAuthKey
* Secret : 先ほどLINE Developer Consoleで作成したChannelのClient Secret
* 用途 : Signature

![LINE鍵の登録](https://github.com/fujie/ts2017/blob/pic/aadb2c_createkey4.png)


#### ポリシーの登録
Azure AD B2CのIEFではカスタム・ポリシーを使ってGUIでは実現出来ない細かな処理を実装します。  
今回はベース・ポリシーと、RP単位に設定するSignup/Signinポリシーの2つを実装します。  
各ポリシーはXMLファイルで構成され、0から作成するのは現実的ではないので、ある程度作成済みのポリシーテンプレートを本レポジトリにアップしてあるので、こちらをダウンロードして構成してください。

* ベース・ポリシー : [policy_template_base.xml](https://github.com/fujie/ts2017/blob/master/policy_template_base.xml)
* Signup/Signinポリシー : [policy_template_susi.xml](https://github.com/fujie/ts2017/blob/master/policy_template_susi.xml)

それぞれの修正点は以下の通りです。  

＜ベース・ポリシー＞

* {your_domain} : 作成したAzure AD B2Cディレクトリのドメイン名へ変更（2か所あります）
* {your_line_client_id} : 作成したLINE LoginのChannel Client IDへ変更（1か所）

＜Signup/Signinポリシー＞

* {your_domain} : 作成したAzure AD B2Cディレクトリのドメイン名へ変更（3か所あります）

