import axios from "axios";

export default class {
    static async check(token) {
        const response = await axios.post('/access-token/check', {
            token
        });
        if (response.data.success === false) {
            throw new Error(response.data.text);
        }
        return response.data.data;
    }
}
