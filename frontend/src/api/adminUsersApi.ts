import { http } from './http'
import type { FitnezUser } from '../types/auth'
import type { Paginated } from '../types/pagination'
import type { Role } from '../types/masterData'

export type TableQuery = {
  search?: string
  role?: string
  status?: string
  page?: number
  per_page?: number
}

function queryString(query: TableQuery) {
  const params = new URLSearchParams()
  Object.entries(query).forEach(([key, value]) => {
    if (value !== undefined && value !== null && value !== '') {
      params.set(key, String(value))
    }
  })
  const text = params.toString()
  return text ? `?${text}` : ''
}

export const adminUsersApi = {
  list(query: TableQuery) {
    return http.get<Paginated<FitnezUser>>(`/admin/users${queryString(query)}`)
  },
  roles() {
    return http.get<Role[]>('/admin/roles')
  },
  create(payload: Record<string, unknown>) {
    return http.post<FitnezUser>('/admin/users', payload)
  },
  update(id: number, payload: Record<string, unknown>) {
    return http.put<FitnezUser>(`/admin/users/${id}`, payload)
  },
  remove(id: number) {
    return http.delete<null>(`/admin/users/${id}`)
  },
}
