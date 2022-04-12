interface ITransfer {
  name: string,
  car_class: string,
  price: number,
  passengers: string,
  luggage: string,
  model: string,
  thumb: string,
  description: string,
  company_name: string,
}

export type RootState = ReturnType<typeof state>;

interface ITransferPayload {
  from: string,
  to: string
}


export const state = () => ({
  transfers: [] as Array<ITransfer>
})


export const mutations = {
  SET_TRANSFERS(state: RootState, payload: Array<ITransfer>) {
    state.transfers = payload;
  }
}
export const actions = {

  fetchTransfers: async ({commit}: any, payload: ITransferPayload) => {
    // @ts-ignore
    const response = await this.$axios.get('/transfers', {
      method: "POST",
      data: {...payload}
    });
    const result: Array<ITransfer> = response.data.data
    commit('SET_TRANSFERS', result)
  }
}
export const getters = {
  transfers: (state: RootState) => state.transfers as Array<ITransfer>
}
