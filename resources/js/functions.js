const host = {
    url: "http://192.168.0.6"
}

export async function fetcher(url, options) {
    const {headers, ...rest} = options;
    console.log(url);
    const init = {
        method: "POST", headers: {
            'Content-Type': 'application/json',
            "Accept": "application/json",
            ...headers
        },
        ...rest
    };
    return await fetch(url, init).then(res=>{
        return res.json()
    }).then(val => {
        return val;
    })
}

export function getApiRoute(route) {
    return  host.url + "/api/" + route;
}

export async function fetcherApi(route, options) {
    return await fetcher(getApiRoute(route), options);
}

export async function fetcherApiAuth(route, options, user) {
    options.headers = {...options.headers, 
        "Authorization": "Bearer " + user.api_token
    };
    return await fetcherApi(route, options);
}

/* export async function axiosApi(route, options) {
    return 
} */