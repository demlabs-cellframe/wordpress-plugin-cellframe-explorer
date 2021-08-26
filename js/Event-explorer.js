function EEInfoFromJSONToHTML(data, link_url){
    var table_hashes = '<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"padding-left:50px;\" class="CE_links_table">'+
        '<thead class="CE_thead"><tr class="CE_th_row"><th class="CE_inf_col_0" style=\"text-align: center;\">Links('+data.dump_data.hashes.length+')</th></tr></thead><tbody class="CE_inf_tbody">';
    data.dump_data.hashes.forEach(function(item){
        var event = item.replace(/\s+/g, '').trim();
        table_hashes += '<tr class="CE_inf_row"><td class="CE_inf_col_link"><a href='+link_url+'&net='+net_selected+'&chain='+chain_selected+'&hash='+event+
            '>'+item+'</a></td></tr>';
    });
    table_hashes += '</tbody></table>';
    var nt = '<table class="event_table" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"padding-left:50px;\"><tbody class="CE_inf_tbody">'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">Event</td>'+
            '<td class="CE_inf_col_2">'+data.dump_data.event+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">Version</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.version)+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">ID Cell</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.cell_id)+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">ID Chain</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.chain_id)+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">TS Create</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.ts_created)+'</td>'+
        '</tr></tbody></table>';
    nt += table_hashes;
    nt += '<table class="CE_datum_table"><tbody class="CE_inf_tbody">'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">Datum size</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.datum_size)+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">Datum version</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.datum_version)+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">Datum type id</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.datum_type_id)+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">Datum TS create</td>'+
            '<td class="CE_inf_col_2">'+data.dump_data.datum_ts_create+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">Datum data size</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.datum_data_size)+'</td>'+
        '</tr>'+
        '<tr class="CE_inf_row">'+
            '<td class="CE_inf_col_1">Sings count</td>'+
            '<td class="CE_inf_col_2">'+String(data.dump_data.signsCount)+'</td>'+
        '</tr>'+
        '</tbody></table>';
    if (data.declaration != null){
        nt += '<table class="CE_sign_table"><tbody class="CE_inf_tbody">'+
            '<tr class="CE_inf_row">'+
                '<td class="CE_inf_col_1">Tiker</td>'+
                '<td class="CE_inf_col_2">'+data.declaration.tiker+'</td>'+
            '</tr><tr class="CE_inf_row">'+
                '<td class="CE_inf_col_1">Size</td>'+
                '<td class="CE_inf_col_2">'+data.declaration.size+'</td>'+
            '</tr><tr class="CE_inf_row">'+
                '<td class="CE_inf_col_1">Type</td>'+
                '<td class="CE_inf_col_2">'+data.declaration.type+'</td>'+
            '</tr><tr class="CE_inf_row">'+
                '<td class="CE_inf_col_1>Sign total</td>'+
                '<td class="CE_inf_col_2">'+data.declaration.sign_total+'</td>'+
            '</tr><tr class="CE_inf_row">'+
                '<td class="CE_inf_col_1">Sign valid</td>'+
                '<td class="CE_inf_col_2">'+data.declaration.sign_valid+'</td>'+
            '</tr><tr class="CE_inf_row">'+
                '<td class="CE_inf_col_1">Total supply</td>'+
                '<td class="CE_inf_col_2">'+data.declaration.total_supply+'</td>'+
            '</tr></tbody></table>';
    }
    return nt;
}
