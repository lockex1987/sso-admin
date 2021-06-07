/**
 * Lấy địa chỉ web socket.
 * @returns Địa chỉ web socket
 */
export const getWsUrl = () => {
    const wsUrl = location.origin.replace(/^http/, 'ws') + '/wsapp/';
    // console.log(wsUrl);
    // Lấy token để xác thực websocket
    const token = localStorage.getItem('authToken');
    return wsUrl + '?token=' + token;
};
