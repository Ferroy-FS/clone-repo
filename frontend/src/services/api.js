const API_URL = '/api'

// ================= TOKEN =================
function getToken() {
  return localStorage.getItem('token')
}

// ================= COMMON HEADERS =================
function getHeaders() {
  return {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
    Authorization: `Bearer ${getToken()}`
  }
}

// ================= HANDLE RESPONSE =================
async function handleResponse(res) {
  const data = await res.json()

  if (!res.ok) {
    console.error('API ERROR:', data)
    throw new Error(data.message || 'API request failed')
  }

  return data
}

// ================= GET =================
export async function apiGet(endpoint) {
  const res = await fetch(`${API_URL}/${endpoint}`, {
    method: 'GET',
    headers: getHeaders()
  })

  return handleResponse(res)
}

// ================= POST =================
export async function apiPost(endpoint, payload = {}) {
  const res = await fetch(`${API_URL}/${endpoint}`, {
    method: 'POST',
    headers: getHeaders(),
    body: JSON.stringify(payload)
  })

  return handleResponse(res)
}

// ================= PUT =================
export async function apiPut(endpoint, payload = {}) {
  const res = await fetch(`${API_URL}/${endpoint}`, {
    method: 'PUT',
    headers: getHeaders(),
    body: JSON.stringify(payload)
  })

  return handleResponse(res)
}

// ================= DELETE =================
export async function apiDelete(endpoint) {
  const res = await fetch(`${API_URL}/${endpoint}`, {
    method: 'DELETE',
    headers: getHeaders()
  })

  return handleResponse(res)
}