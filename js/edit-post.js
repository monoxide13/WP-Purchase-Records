$j=jQuery.noConflict();

//$j("#pr_i_ab").click(purchase_records_add_meta_row());

function purchase_records_add_meta_row(){
	var adding = `<tr><td class='pr_c1'><button id='pr_i_rb[]' name='pr_i_rb_2' class='purchase_records_metabox_item_line' type='button' onclick='purchase_records_remove_meta_row(jQuery(this))'>-</button></td>
		<td class='pr_c2'><input id='pr_i_id[]' name='pr_i_id[]' type='number' value='0' class='purchase_records_metabox_item_table_items' readonly='true'></td>
		<td class='pr_c3'><input id='pr_i_nm[]' name='pr_i_nm[]' type='textbox' value='' class='purchase_records_metabox_item_table_items' ></td>
		<td class='pr_c4'><input id='pr_i_is[]' name='pr_i_is[]' type='checkbox' value='0' class='purchase_records_metabox_item_table_items' style='position:relative;left:25%;'></td>
		<td class='pr_c5'><input id='pr_i_cs[]' name='pr_i_cs[]' type='number' value='' class='purchase_records_metabox_item_table_items' step='0.01'></td>
		<td class='pr_c6'><input id='pr_i_qt[]' name='pr_i_qt[]' type='number' value='1' class='purchase_records_metabox_item_table_items' step='1'></td>
		<td class='pr_c7'><input id='pr_i_wl[]' name='pr_i_wl[]' type='textbox' value='' class='purchase_records_metabox_item_table_items' ></td></tr>`;
		$j("#purchase_records_metabox_items_table").append(adding);
}

function purchase_records_remove_meta_row(rRow){
	//$j(this).parent().parent().remove();
	rRow.closest("tr").remove();
}
