<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" encoding="UTF-8" indent="yes"/>

<xsl:template match="/">
    <html>
    <head>
        <title>Workers List</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(to right, #f8f9fa, #e3f2fd);
                padding: 40px;
            }
            h2 {
                text-align: center;
                color: #333;
                margin-bottom: 30px;
            }
            table {
                width: 70%;
                margin: auto;
                border-collapse: collapse;
                box-shadow: 0 0 10px rgba(0,0,0,0.2);
                background-color: #fff;
            }
            th, td {
                padding: 12px 18px;
                border: 1px solid #ccc;
                text-align: left;
            }
            th {
                background-color: #007bff;
                color: white;
            }
            tr:nth-child(even) {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <h2>List of Workers</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
            </tr>
            <xsl:for-each select="workers/worker">
                <tr>
                    <td><xsl:value-of select="name"/></td>
                    <td><xsl:value-of select="email"/></td>
                </tr>
            </xsl:for-each>
        </table>
    </body>
    </html>
</xsl:template>

</xsl:stylesheet>
