<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns="http://www.w3.org/1999/xhtml" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:bong="http://lab.zigmoyd.net/xmlns/bong">
<xsl:output method="xml" version="1.0" encoding="UTF-8" indent="yes" />				
<xsl:strip-space elements="*" />
  <xsl:template match="*|@*">
    <xsl:copy>
      <xsl:apply-templates select="@*"/>
      <xsl:apply-templates/>
    </xsl:copy>
  </xsl:template>
  <xsl:template match="processing-instruction()|comment()">
    <xsl:copy>.</xsl:copy>
  </xsl:template>
  <xsl:template match="bong:xdo">
  <?php echo $this->viewContents ?>
  </xsl:template>
</xsl:stylesheet>
