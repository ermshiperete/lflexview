<p>Some search results</p>
<table>
<? $i = 1; if ($SearchResults) foreach($SearchResults as $result):
	$lexicalUnit = $result->getSpace('lexical-unit'); ?>
<tr class="<? echo "r".$i%2; ?>">
	<td><? echo $i; ?></td>
	<td><? echo $lexicalUnit->get(LANG_Vernacular); ?></td>
	<td><? echo $lexicalUnit->get(LANG_IPA); ?></td>
</tr>
<? $i++; endforeach; ?>
</table>