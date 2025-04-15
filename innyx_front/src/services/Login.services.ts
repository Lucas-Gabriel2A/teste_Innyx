import { apiin } from "./AxiosCreate.services";

export const login = async (username: string, password: string) => {
    const response = await apiin.post("/login", {
        username,
        password,
    });
    return response.data;
};