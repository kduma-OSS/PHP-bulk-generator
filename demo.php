<?php

use Kduma\BulkGenerator\ContentGenerators\TwigTemplateContentGenerator;
use Kduma\BulkGenerator\DataSources\PassthroughDataSource;
use Kduma\BulkGenerator\PdfGenerators\MpdfGenerator;
use Kduma\BulkGenerator\PageOptions\PageSize;
use Kduma\BulkGenerator\BulkGenerator;

require __DIR__.'/vendor/autoload.php';

$value = <<<SAMPLE_DATA
79C4BA2F-A922-F7A4-24BD-56974973AC5B|Kathleen Parks|High Level
1D8769A6-AE6F-B2D7-A268-BB5C934E758D|Fuller Farmer|Kessel
D3752C8A-D817-25CB-AD86-74941F839EBF|Karen Guthrie|Dietzenbach
4A1481D4-FAF2-17B8-1833-61C3E3C3AFAA|Beck Joyner|Leamington
67CB9846-B50C-E7AF-8963-3D7F66FBE90A|Tasha Leon|Fontaine-l'Evque
A4D4E8E3-EABC-12BF-1AFA-9B86BDC20153|Harrison Ferrell|Roccasicura
C081478D-B64F-F66A-52A6-A9A7B7FBFD65|Hope Giles|South Dum Dum
E1840894-05F4-D9F3-E14A-37E26D95E744|Iona Atkins|Rhemes-Saint-Georges
EA080CA4-BBD6-64A3-14CB-0B07501A3224|Kelsey Turner|Kawartha Lakes
CB8DE2AA-34CC-EE8B-42CD-DB2001B92C4D|Burke Morales|Allentown
4A1BD403-5167-300F-2CBF-D2E079C2FE88|Chantale Ellis|Sitapur
B9D7AA51-C833-7D82-56A7-6B8BBC25ACE4|Stone Pugh|Tallahassee
99BAAA15-47BA-4CDF-DBA7-0E8E63E9AA4D|Kyle Wilson|Viano
C66B3D28-7662-FEE6-ED73-DA4EB0F12602|Shoshana Bonner|Wha Ti
A0B27F46-4AD4-2F1C-4C02-4105ED450804|Zenia Mays|Nocera Umbra
A027BE51-DC7F-9E7F-792D-FFBDC7841AE5|Mason Lowery|Bodmin
95AC850B-4F52-B449-2CF8-5685F9A49AC6|Rana Buchanan|Paradise
F7DE8400-6055-1474-9078-CDD8514BB73D|Rudyard Savage|Luton
63C42037-BA4F-14BC-5CDA-B93603AA4D9F|Justina Marquez|Colorado Springs
26BA7222-C53C-EC1E-61E1-37596BE84C1F|Idola Weeks|Saskatoon
EAC21633-A242-B3BC-3454-F4F8136D2531|Catherine Moss|Lago Verde
5CF9840E-B233-DD56-A87A-FE2103FC5469|Patricia Pickett|Montería
E37C66B8-BA34-6FFC-27E2-B61A8632C82E|Edward Conley|San Jose
E9832A7C-0948-7FC8-347F-6643B09F2A17|Adena Buckley|San Marcello Pistoiese
94146D64-EBA4-7A51-AE5B-43FE1D0D00D4|Marny Bradley|Capannori
A064B004-1FD5-DA92-D15A-41FA7EE78248|Kuame Mccullough|Lambusart
34BFA092-8423-E444-75FC-3F34E6A6B53A|Xenos Mckinney|Flint
861FAEDE-EF40-EB26-147B-7212CDE7D0F0|Brennan Monroe|Malegaon
A7217FBD-57FE-B423-191C-6D4F32832269|Finn Barrera|Bienne-lez-Happart
09C7AC23-11C4-7A1D-4B71-16DCABD5407A|Vincent Walker|Cimolais
C09FC510-1FCC-9351-E175-5C40D12DEEF1|Myles Vaughan|Thuin
96D62B75-383F-907F-850C-A58D23063E2E|Hiram Webb|Berlin
90D50439-C111-71C9-E1DB-D93D50947BC9|Velma Clements|Wenduine
8F201E92-5FAE-39C7-18EC-D52F5FD4C968|Gary Rowland|Sivry-Rance
8352C449-2C9C-EB62-42F6-8E4CA6EF22AE|Lucy Collier|Rebecq
54A84999-7AD1-B97D-4B11-F4072417D45C|Heidi Parsons|Ödemiş
65D454C8-A489-5846-CAB0-F83F3C50D149|Yoshio Bowen|Knittelfeld
D3F4AC41-6300-63F9-6D77-38E800384CF4|Xena Oneal|Velden am Wörther See
B42A3461-B458-082D-5093-9B556B81E9C9|Blossom Sargent|Geel
0E3D1011-8090-3E4A-D554-49886A74A8AF|Julie Nixon|Township of Minden Hills
138412B9-18F3-3B50-B09D-3D3A1B8B1AA1|Abbot Casey|Zerkegem
40F98245-BC7C-7FB8-2ABA-D0311DA50A9E|Evangeline Lambert|Villers-la-Ville
0CDD507C-1B1B-1BEE-9845-3B2863843156|Karina Guzman|Solvychegodsk
65AA3CEA-A795-4D73-CD4B-E2C13725233B|Freya Serrano|Amritsar
7E7F6345-4208-2C7C-12EB-7B3AD2DF5FA2|Daniel Acevedo|Surat
3BF0DB00-7633-F36B-687F-417EA7214E80|Kelly Juarez|Konin
725938A0-ACCD-7832-CCF8-EB7DD9D79BFC|Zelda Good|Acosse
534EBD5C-2545-B8A1-A55A-D077FF423E3B|Graham Avery|Lexington
A0C75769-7522-0159-6835-2D1560793A97|Rhiannon Chambers|Bozeman
6F7FB79F-5391-17F5-D2E5-FB951E3C2498|Shelly Page|Vorst
72BF2AB3-603C-9118-AF57-D36C5BC7630D|Amir Kerr|Castel di Tora
9B7DB5BE-6AA6-8E24-7591-4678D7FD5CAD|Regan Brady|Overland Park
F248553A-268C-AE65-EC39-8781A416717D|Tanek Moses|Darion
649AB51C-01E7-5158-EAD4-71E4D7597880|Bertha Kerr|San Fabián
90269FB3-1E09-11A2-C234-F060E8C090D0|Catherine Mitchell|Halesowen
04227DB8-B4E0-18A7-198A-DA079C575224|Matthew Marsh|Montegranaro
E28C66FC-04D4-1E36-85BC-511A66EE8AB6|Dalton Neal|San Juan del Río
7CD50977-5150-7318-3261-DFB9EC08BDD1|Fuller Larsen|Dietzenbach
30150B1F-EAA0-57DE-9E6E-1E181B5CA1F1|Regina Cantu|Eindhout
4A8CDAA0-4BBE-06A8-4601-1E1E7B384283|Latifah Madden|Machalí
BE4D6723-5118-8615-1FCF-1AE1B63002A7|Beck Ochoa|San Pietro al Tanagro
22C14AF6-17A0-EA96-64C3-B714538E5E09|Dora Knox|Lake Cowichan
FDAE36F6-861B-A165-197A-06FE1F276483|Janna Reynolds|Piedras Negras
B0403700-41BA-7685-DF85-B3B77D25BDC1|Halla Norton|Rinconada
85C68ED7-0EE5-3567-E4BD-BBF800DB0169|Chiquita Zamora|Shchyolkovo
246924A2-707E-F46B-369F-06B6E2B7B500|Dane Cherry|Évreux
FB8884FB-C69E-7CEF-084E-37800E09B3B0|Ella Boone|Okotoks
F732982D-5448-3CCC-F845-DC9E9C3C83CB|Honorato Salas|Eugene
7962F1CB-20CF-0251-8FB5-217CC2B7C17E|George Stanton|Lahore
D5A22CD3-2254-766F-6CC3-3AA221E9F1E6|Ciaran Carver|Welland
6B971B90-FB8A-6AE8-E3AB-99A35048EC47|Deanna Reynolds|Chambord
A9C2200D-2FFC-EB24-2DA2-4CBCD77FEB99|Nasim Larsen|Newcastle
7A9C8A58-C4E1-796E-5B69-0A9C26F90AF7|Kirsten Soto|Latinne
CD5EB43B-776E-8F4F-B902-6054DC9EED64|Timothy Mason|Inírida
E1F7D4B0-B223-8A51-95A7-E4BCCFA9690B|Carter Hancock|Gangneung
82C64AA0-E9C6-777C-4A8C-D5C38BA66E3F|Eaton Ratliff|Ramara
F68E4E69-7C87-B5B7-F88A-C74C3C9D3225|Martin Campbell|Saint-Georges
617A77D3-4253-4F23-EDCB-9F64248B44E8|Tanek Bonner|Robelmont
626D26B3-4EB3-1E67-471F-5483002C8585|Xaviera Barr|Cork
6DB558DB-FF26-7EF6-E086-E3B0DDFB6F5A|Joel Richard|Parrano
DEC9F37B-3706-4B0A-04D1-DD914142B959|Lucius Mckinney|Stornaway
D6904362-C551-D4EA-340B-D333CBABB125|Keelie Soto|Goiânia
85EFEF81-6A76-9F95-5760-8A51631D2423|Lynn Simon|Viggianello
DD20779F-3DDC-80D5-23F6-1EA67AC7A76A|Jeanette Pugh|Grobbendonk
C23E8325-19F6-DBB6-36FF-3712185E9295|Jorden Poole|Bleid
CDB7847E-3B4A-29F6-78A8-4782295B01BD|Ashely Obrien|Bathgate
7809512E-3B38-2A79-CAF6-C127967D4880|Gavin Trevino|Genval
E07E52CF-9E4C-5847-FE89-E60D8A394EAF|Rhonda Chan|Spalbeek
7C518F41-23E2-CBB5-D25E-55A44FA0BD22|Basil Conner|Massemen
E7A8200B-C38E-660C-4F26-24CD610BE88B|Gavin Tanner|Graz
FADA7338-25D2-B425-C9AC-B33FF9A6FE6F|Colton Cleveland|Ferrere
E478A971-C16B-D693-4EFD-E60B5FFBD0F2|Jaquelyn Francis|Brentwood
33896C5E-EC5B-D9A1-E5CD-7757185958F0|Halla Shelton|Calera
8AAF61A9-A876-428D-EC36-684953A5F28D|Barrett Trujillo|Sierning
64D6A5FF-0F30-27E8-43EE-079584B5D73E|Garrison Juarez|Canora
A1AABA32-7F38-527D-57A6-5D22199C4AD7|Curran Owen|Stirling
DDE90511-DFE6-897C-D534-556E73A51298|Uriah Glover|Coleville Lake
56E21CD8-7D34-5AAF-4FF2-4D51DB76A921|Rebekah Torres|Courbevoie
47BAB616-1455-1044-2AF9-3CE182866CC4|Oleg Deleon|Enterprise
462E1284-86D5-AF0F-74D1-CD0C58D1B93A|Emerson Nguyen|Namur
SAMPLE_DATA;


$dataSource = new PassthroughDataSource(
    collect(explode("\n", $value))
        ->map(fn($row) => explode("|", $row))
        ->map(fn($row) => array_combine(['uuid', 'name', 'city'], $row))
);

$pdfGenerator = new MpdfGenerator(new PageSize(100, 55));
$pdfGenerator->setCss(/** @lang CSS */ <<<CSS
    .key, .id {
        font-family: monospace; 
    }
    
    .key {
        font-size:6mm; 
    }
    
    .id {
        font-size:2.5mm; 
        color: gray;
    }
    
    .center {
        vertical-align: middle;
        text-align: center;
    }
    CSS
);

$content = new TwigTemplateContentGenerator(/** @lang Twig */ <<<Twig
    {% box 5, 5, 90 with {"class": ["key"]} %}
    <strong>Name:</strong>
    {{ name }}
    {% endBox %}
    
    {% box 5, 25, 90 with {"class": "key"} %}
    <strong>City:</strong>
    {{ city }}
    {% endBox %}
    
    {% box 5, 47.5, 90 with {"class": "id"} %}
    {{ uuid }}
    {% endBox %}
    Twig
);

$generator = (new BulkGenerator($dataSource, $pdfGenerator))
    ->setFrontContentGenerator($content);

$generator->generate(__DIR__.'/output.pdf');

$back_content = new TwigTemplateContentGenerator(/** @lang Twig */ <<<Twig
    {% box 0, 0, 100, 55 with {"class": "id center"} as table %}
        <strong>Back of</strong>
        <br>
        <br>
        {{ uuid }}
    {% endBox %}
    Twig
);

$generator = (new BulkGenerator($dataSource, $pdfGenerator))
    ->setFrontContentGenerator($content)
    ->setBackContentGenerator($back_content);

$generator->generate(__DIR__.'/output_duplex.pdf');