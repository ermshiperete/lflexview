<p>Some search results</p>
<table>
<? $i = 1; if ($SearchResults) foreach($SearchResults as $result):
	$lexicalUnit = $result->getSpace('lexical-unit'); ?>
<tr class="<? echo "r".$i%2; ?>">
	<td><? echo $i; ?></td>
	<td><a href="<?php echo $urlMapper->write(ActionPath::fromString('Page/LexicalEntry'), new Command('read', array($result->getID()))); ?>"><? echo $lexicalUnit->get(LANG_Vernacular); ?></a></td>
	<td><? echo $lexicalUnit->get(LANG_IPA); ?></td>
</tr>
<? $i++; endforeach; ?>
</table>