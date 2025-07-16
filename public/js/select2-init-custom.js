// public/js/select2-init-custom.js

window.select2Custom = function (
    selector,
    url,
    propertyShow = "nama",
    placeholder,
    dropdownParent = null,
) {
    const localCache = {};

    $(selector).select2({
        placeholder: placeholder ? placeholder : "Pilih...",
        allowClear: true,
        dropdownParent: dropdownParent ? $(dropdownParent) : null,
        width: "100%",
        ajax: {
            url: url,
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page || 1,
                };
            },
            transport: function (params, success, failure) {
                const page = params.data.page || 1;
                const q = params.data.q || "";
                const cacheKey = `${q}_page_${page}`;

                if (localCache[cacheKey]) {
                    console.log(localCache);
                    success(localCache[cacheKey]);
                    return;
                }

                const $request = $.ajax(params);
                $request
                    .then(function (data) {
                        localCache[cacheKey] = data;
                        success(data);
                    })
                    .fail(failure);

                return $request;
            },
            processResults: function (data) {
                return {
                    results: data.data.map((item) => {
                        return {
                            id: item.id,
                            text: item[propertyShow],
                        };
                    }),
                    pagination: {
                        more: data.next_page_url !== null,
                    },
                };
            },
            cache: true,
        },
        dropdownCssClass: "select-scroll",
        language: {
            loadingMore: () => "Memuat...",
            searching: () => "Mencari...",
            noResults: () => "Tidak ada hasil",
            inputTooShort: () => "Ketik untuk mulai mencari",
        },
    });
};
