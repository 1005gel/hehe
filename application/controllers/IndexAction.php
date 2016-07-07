<?php
//默认控制器application/controllers/Index.php
class IndexController extends Yaf_Controller_Abstract {
   public function indexAction() {//默认Action
   	echo "123";
       $this->getView()->assign("content", "Hello World");
   }
}
?>


# 常用的爬虫技巧

**爬虫**网络爬虫（又被称为网页蜘蛛，网络机器人），是一种按照一定的规则，自动的抓取万维网信息的程序或者脚本，是搜索引擎的重要组成。传统爬虫从一个或若干初始网页的URL开始，获得初始网页上的URL，在抓取网页的过程中，不断从当前页面上抽取新的URL放入队列，直到满足系统的一定停止条件。

-------------------

##基本流程

![Alt text](./spider3.jpg)

上图是一般爬虫的基本流程，本文主要介绍其中的**网页获取**和**网页解析**。

###网页获取
网页获取一般有以下几种情况：
- **get**
- **post**
- **cookies**
- **header**
- **ip代理**
- **gzip**

用浏览器控制台观察目标网站，观察请求信息模拟获取网页内容，其中 get 方法可以直接获取的最为简单，直接访问即可。

部分网页可能需要 post 方法带参数获取，可以用 postman 模拟。

还有些网站需要登录才能获取信息，这时需要携带 cookies 信息来模拟请求。
修改 header 主要可能包括 User-Agent , Referer 和 Content-Type 这里可能需要多次尝试，有些网站会自定义 header 字段也是需要注意，不同网站验证的情况不一样。header 还可以模拟成搜索引擎抓取目标网站来降低被目标网站防爬的几率。
开发爬虫大量抓取网站还有ip被封禁的可能，这时就需要用到ip代理去获取目标网站。另外有些网站进行了 gzip 压缩优化网站体验，这时抓取的内容总是一团乱码，需要先解压一下。

爬取过高，可能会被对方服务器限制，因此可能需要对爬取频率做一些调整以及使用一些代理来进行爬取。

需要注意一下目前有很多网站分页加载是ajax异步请求，有的是 JSON 返回，有的是html片段。也有JavaScript代码加载内容，这时可能需要用到 [phantomJS](http://phantomjs.org/) 来进行渲染解析。
###网页解析
下面来说一下网页解析：
- **HTML DOM 解析**
- **Xpath**
-  **正则**
- **CSS选择器**
- **JSON XML**

html ：h2.getElementsByTag("span").first().text();
Xpath： /center/table[@id='hnmain']/tbody/tr[3]/td/table[@class='itemlist']/tbody/tr[@class='athing']/td[@class='title'][2]/a chrome 命令行可以直接获取，推荐 chrome 扩展 Xpathhelper 实际使用中可以精简一下 
![Alt text](./xpath.jpg)

css 选择器： 比如 #username
正则表达式：用标准正则解析，一般会把 HTML 当做普通文本，用指定格式匹配当相关文本，适合小片段文本，或者某一串字符，或者 HTML 包含 javascript 的代码，无法用 CSS 选择器或者 Xpath 。
JSON 和 XML 结构化数据在网站上较为少见，不过解析起来更为方便。

如果获取的目标内容同时有 web 和 app 更推荐通过 fiddler 抓包的方式获取到接口地址来抓取数据。JSON数据推荐使用chrome 扩展 jsonview 观察分析数据，XMl也有相应扩展，接口形式变动少，即便升级也一般也都会保留原有接口，多数为增加属性，不会影响到数据抓取脚本，效率更高。

## 实际使用

大量抓取数据实际使用中还需要注意一些问题，抓取效率取决于双方的网络情况，监控自己的带宽。抓取过程中内存可能会占用越来越多，要及时释放掉用过的资源。一些时效性要求比较高的数据需要及时获取，有可能需要将脚本拆分成更小的单元，多线程同时进行。每天同步一次的数据，可以考虑在对方服务器负载较低的时候进行抓取。错误收集和失败重连机制还有自己的终止条件设定也很重要，每天都需要更新的数据，还需要监控每天实际爬取的总量。








