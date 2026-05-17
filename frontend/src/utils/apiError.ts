export function getApiErrorMessage(error:unknown, fallback='Request failed'){ const e=error as {payload?:{message?:string};message?:string}; return e?.payload?.message || e?.message || fallback }
