import { authApi } from '../api/authApi'
import type { RegisterProspectiveMemberPayload } from '../types/auth'

export const authService = {
  async registerProspectiveMember(payload: RegisterProspectiveMemberPayload) {
    return authApi.registerProspectiveMember(payload)
  },

  async requestLoginOtp(email: string, password: string) {
    return authApi.requestLoginOtp(email, password)
  },

  async verifyLoginOtp(email: string, otp: string) {
    const response = await authApi.verifyLoginOtp(email, otp)
    return response.data
  },

  async forgotPassword(email: string) {
    return authApi.forgotPassword(email)
  },

  async resetPassword(email: string, otp: string, password: string, passwordConfirmation: string) {
    return authApi.resetPassword(email, otp, password, passwordConfirmation)
  },

  async memberLogin(email: string, password: string) {
    const response = await authApi.memberLogin(email, password)

    if (response.data.access_token) {
      localStorage.setItem('fitnez_access_token', response.data.access_token)
    }

    return response.data
  },

  async logout() {
    await authApi.logout().catch(() => null)
    localStorage.removeItem('fitnez_access_token')
  },
}
