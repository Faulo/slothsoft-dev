<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
	xmlns:html="http://www.w3.org/1999/xhtml"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:sfm="http://schema.slothsoft.net/farah/module"
	xmlns:sfs="http://schema.slothsoft.net/farah/sites"
	xmlns:lio="http://slothsoft.net"
	xmlns:exsl="http://exslt.org/common"
	xmlns:func="http://exslt.org/functions" xmlns:str="http://exslt.org/strings"
	extension-element-prefixes="exsl func str">
	
	<xsl:param name="name"/>
	<xsl:param name="href"/>
	<xsl:param name="adapter"/>
	<xsl:param name="type"/>
	
	<xsl:param name="dataUrl"/>
	<xsl:param name="templateUrl"/>
	
	<xsl:variable name="config" select="/config"/>
	
	

	<func:function name="lio:contains">
		<xsl:param name="a"/>
		<xsl:param name="b"/>

		<func:result select="contains(lio:normalize($a), lio:normalize($b))" />
	</func:function>
	<func:function name="lio:equals">
		<xsl:param name="a"/>
		<xsl:param name="b"/>

		<func:result select="lio:normalize($a) = lio:normalize($b)" />
	</func:function>
	<func:function name="lio:normalize">
		<xsl:param name="a"/>
		
		<func:result select="normalize-space(translate(normalize-space($a), 'abcdefghijklmnopqrstuvwxyz:', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ '))"/>
	</func:function>
	
	<xsl:template match="/">
		<source name="{$name}" href="{$href}" adapter="{$adapter}" type="{$type}">
			<xsl:for-each select="$config/hero-list/hero">
				<xsl:variable name="heroName" select="@name"/>
				<hero name="{$heroName}">
					<xsl:for-each select="$config/hero-list/hero">
						<xsl:variable name="otherName" select="@name"/>
						<other-hero name="{$otherName}" counter-value="{lio:calculate-counter($heroName, $otherName)}"/>
					</xsl:for-each>
				</hero>
			</xsl:for-each>
		</source>
	</xsl:template>
</xsl:stylesheet>
