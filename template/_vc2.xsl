<?xml version="1.0" encoding="UTF-8"?><xsl:stylesheet version="1.0"	xmlns="http://www.w3.org/1999/xhtml"	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"> 	<xsl:template match="/data">		<html>			<head><title>Slothsoft - VC2</title>			<style type="text/css"><![CDATA[table {	width: 6cm;	display: inline-table;	margin: 1cm;}select {	width: 100%;	display: block;}			]]></style>			</head>						<body>				<xsl:for-each select=".//resource">					<table border="1">						<thead>							<tr>								<th>Character</th>								<th>Crtfcate</th>								<th>Diploma</th>								<th>Arms</th>								<th>Attk</th>								<th>Mrch</th>								<th>Sppt</th>							</tr>						</thead>						<tbody>							<xsl:for-each select=".//*[@class]">								<tr>									<!--<td><a href="http://valkyria.wikia.com/wiki/{@character}"><xsl:value-of select="@character"/></a></td>-->									<td><xsl:value-of select="@character"/></td>									<!--<td><xsl:apply-templates select="." mode="select"/></td>-->									<th><input type="checkbox"/></th>									<th><input type="checkbox"/></th>									<th><input type="checkbox"/></th>									<th><input type="checkbox"/></th>									<th><input type="checkbox"/></th>									<th><input type="checkbox"/></th>								</tr>							</xsl:for-each>						</tbody>					</table>				</xsl:for-each>			</body>		</html>	</xsl:template>		<xsl:template match="*[@class = 'Scout']" mode="select">		<select>			<option><xsl:value-of select="@class"/></option>			<option>Scout Elite</option>			<option>Heavy Scout</option>			<option>Sniper Elite</option>			<option>AT Sniper</option>		</select>	</xsl:template>	<xsl:template match="*[@class = 'Trooper']" mode="select">		<select>			<option><xsl:value-of select="@class"/></option>			<option>Trooper Elite</option>			<option>Commando</option>			<option>Gunner Elite</option>			<option>Heavy Gunner</option>		</select>	</xsl:template>	<xsl:template match="*[@class = 'Lancer']" mode="select">		<select>			<option><xsl:value-of select="@class"/></option>			<option>Lancer Elite</option>			<option>Mobile Lancer</option>			<option>Heavy Mortar</option>			<option>Mobile Mortar</option>		</select>	</xsl:template>	<xsl:template match="*[@class = 'Engineer']" mode="select">		<select>			<option><xsl:value-of select="@class"/></option>			<option>Engineer Elite</option>			<option>Medic</option>			<option>Anthem Elite</option>			<option>Melodist</option>		</select>	</xsl:template>	<xsl:template match="*[@class = 'Tech']" mode="select">		<select>			<option><xsl:value-of select="@class"/></option>			<option>Tech Elite</option>			<option>Special Tech</option>			<option>Fencer Elite</option>			<option>Mauler</option>		</select>	</xsl:template></xsl:stylesheet>