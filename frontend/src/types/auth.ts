export type FitnezUser = {
  id: number
  email: string
  full_name: string
  phone?: string | null
  role: 'admin' | 'trainer' | 'member' | string
  role_id?: number
  is_active: boolean
  email_verified_at?: string | null
  trainer_status?: 'not_submitted' | 'pending' | 'approved' | 'rejected' | string
  can_access_trainer_workspace?: boolean
  trainer_application_id?: number | null
}

export type LoginResult = {
  token_type: 'Bearer'
  access_token: string
  user: FitnezUser
}

export type RegisterProspectiveMemberPayload = {
  full_name: string
  email: string
  phone?: string
  password: string
  password_confirmation: string
}
