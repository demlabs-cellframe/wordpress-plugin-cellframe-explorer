function EEInfoFromJSONToHTML(data, link_url){
    var table_hashes = '<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"padding-left:50px;\">'+
        '<thead><tr><th style=\"text-align: center;\">Links('+data.dump_data.hashes.length+')</th></tr></thead><tbody>';
    data.dump_data.hashes.forEach(function(item){
        var event = item.replace(/\s+/g, '').trim();
        table_hashes += '<tr><td><a href='+link_url+'&net='+net_selected+'&chain='+chain_selected+'&hash='+event+
            '>'+item+'</a></td></tr>';
    });
    table_hashes += '</tbody></table>';
    var nt = '<table cellpadding=\"5\" cellspacing=\"0\" border=\"0\" style=\"padding-left:50px;\"><tbody>'+
        '<tr>'+
            '<td>Event</td>'+
            '<td>'+data.dump_data.event+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Version</td>'+
            '<td>'+String(data.dump_data.version)+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>ID Cell</td>'+
            '<td>'+String(data.dump_data.cell_id)+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>ID Chain</td>'+
            '<td>'+String(data.dump_data.chain_id)+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>TS Create</td>'+
            '<td>'+String(data.dump_data.ts_created)+'</td>'+
        '</tr></tbody></table>';
    nt += table_hashes;
    nt += '<table><tbody>'+
        '<tr>'+
            '<td>Datum size</td>'+
            '<td>'+String(data.dump_data.datum_size)+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Datum version</td>'+
            '<td>'+String(data.dump_data.datum_version)+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Datum type id</td>'+
            '<td>'+String(data.dump_data.datum_type_id)+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Datum TS create</td>'+
            '<td>'+data.dump_data.datum_ts_create+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Datum data size</td>'+
            '<td>'+String(data.dump_data.datum_data_size)+'</td>'+
        '</tr>'+
        '<tr>'+
            '<td>Sings count</td>'+
            '<td>'+String(data.dump_data.signsCount)+'</td>'+
        '</tr>'+
        '</tbody></table>';
    if (data.declaration != null){
        nt += '<table><tbody>'+
            '<tr>'+
                '<td>Tiker</td>'+
                '<td>'+data.declaration.tiker+'</td>'+
            '</tr><tr>'+
                '<td>Size</td>'+
                '<td>'+data.declaration.size+'</td>'+
            '</tr><tr>'+
                '<td>Type</td>'+
                '<td>'+data.declaration.type+'</td>'+
            '</tr><tr>'+
                '<td>Sign total</td>'+
                '<td>'+data.declaration.sign_total+'</td>'+
            '</tr><tr>'+
                '<td>Sign valid</td>'+
                '<td>'+data.declaration.sign_valid+'</td>'+
            '</tr><tr>'+
                '<td>Total supply</td>'+
                '<td>'+data.declaration.total_supply+'</td>'+
            '</tr></tbody></table>';
    }
    return nt;
}
