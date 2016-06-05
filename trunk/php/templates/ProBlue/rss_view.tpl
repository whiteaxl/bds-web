<?xml version="1.0" encoding="{L_CHARSET}"?>
<rss version="2.0">
<channel>
	<title>{CAT_TITLE}</title>
	<description>{CAT_DESC}</description>
	<link>{U_CAT_VIEW}</link>
	<docs>http://blogs.law.harvard.edu/tech/rss</docs>
	<generator>{SCRIPT_NAME}</generator>
	<!-- START: articlerow -->
	<item>
		<title>{articlerow:TITLE}</title>
		<description>{articlerow:PREVIEW}</description>
		<link>{articlerow:U_VIEW}</link>
		<pubDate>{articlerow:DATE}</pubDate>
		<!-- START: articlerow:enclosure -->
		<enclosure url="{articlerow:enclosure:IMAGE_URL}" type="{articlerow:enclosure:IMAGE_TYPE}" />
		<!-- END: articlerow:enclosure -->
	</item>
	<!-- END: articlerow -->
</channel>
</rss>