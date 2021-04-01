function TxInfoFromJSONToHTML(data, address_info_url){
    var hash = "<div class='data_container'><h3 class='name_data_container'>Hash</h3>"+data.hash+"</div>";
    var tables = "";
    data.items.forEach(function(element){
        switch(element.TYPE){
            case 'TOKEN':
                tables += "<div class='data_container'><h3 class=\'name_data_container\'>Token</h3>"+
                    "<table class='tx_table_addons_info'><tbody>" +
                    "<tr><td>Ticker</td><td>"+element.ticker+"</td></tr>"+
                    "<tr><td>Emission hash</td><td>"+element.token_emission_hash+"</td></tr>"+
                    "<tr><td>Emission chain id</td><td>"+element.token_emission_chain_id+"</td></tr>"+
                    "</tbody></table></div>";
                break;
            case "IN":
                tables += "<div class='data_container'><h3 class=\'name_data_container\'>IN</h3>"+
                    "<table class='tx_table_addons_info'><tbody>"+
                    "<tr><td>tx prev hash</td><td>"+element.tx_prev_hash+"</td></tr>"+
                    "<tr><td>tx out prev idx</td><td>"+element.tx_out_prev_idx+"</td></tr>"+
                    "</tbody></table></div>";
                break;
            case "OUT":
                var address = element.address.replace(/\s+/g, '').trim();
                tables += "<div class='data_container'><h3 class=\'name_data_container\'>OUT</h3>"+
                    "<table class='tx_table_addons_info'><tbody>"+
                    "<tr><td>Value</td><td>"+element.value+"</td></tr>"+
                    "<tr><td>Address</td><td><a href='"+address_info_url+"?addr="+address+"'>"+element.address+"</a></td></tr>"+
                    "</tbody></table></div>";
                break;
            case "SIG":
                tables += "<div class='data_container'><h3 class=\'name_data_container\'>SIG</h3>"+
                    "<table class='tx_table_addons_info'><tbody>"+
                    "<tr><td>SIG size</td><td>"+element.size+"</td></tr></tbody></table>"+
                    "<br/>"+
                    "<div class='data_container'><h3 class='name_data_container'>Signature</h3>"+
                    "<table class='tx_table_addons_info'><tbody>"+
                        "<tr><td>type</td><td>"+element.Signature.type+"</td></tr>"+
                        "<tr><td>Public key hash</td><td>"+element.Signature.pubKey.hash+"</td></tr>"+
                        "<tr><td>Public key size</td><td>"+element.Signature.pubKey.size+"</td></tr>"+
                        "<tr><td>Size</td><td>"+element.Signature.size+"</td></tr>"+
                    "</tbody></table>"+
                    "</div>"+
                    "</tr>"+
                    "</tbody></table></div>";
                break;
        }
    });
    var ret = hash + tables;
    return ret;
}
