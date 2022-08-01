import axios from "axios";

export default class {
    static async send(vkGroupId) {
        const response = await axios.get('/api/group/read/' + vkGroupId);
        if (response.data.success === false) {
            throw new Error(response.data.text);
        }
        return response.data.data;
    }
}
