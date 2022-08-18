import axios from "axios";

export default class {
    static async add(vkGroupId, vkClientId, vkAccessToken) {
        const response = await axios.post('/api/group/create', {
            vk_group_id: vkGroupId,
            vk_client_id: vkClientId,
            vk_access_token: vkAccessToken
        });
        if (response.data.success === false) {
            throw new Error(response.data.text);
        }
        return response.data.data.group;
    }
}
