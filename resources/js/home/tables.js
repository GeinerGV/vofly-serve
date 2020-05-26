$("#len_rows").on("change", function() {
    const {search} = location;
    const len = this.value;
    const params = new URLSearchParams(search ? search.substr(1, length) : "");
    if (len) params.set("len", len);
    else params.delete("len");
    const str_params = params.toString();
    const url = location.origin+location.pathname + (str_params ? '?' + str_params : "");
    console.log(url)
    location.href = url;
});