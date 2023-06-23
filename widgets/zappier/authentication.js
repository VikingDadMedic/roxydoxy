const baseApiUrl = process.env.API_URL
  ? process.env.API_URL
  : 'https://api.roxie.ai';

module.exports = {
  type: 'custom',
  test: {
    headers: { Authorization: 'Bearer {{bundle.authData.api_key}}' },
    removeMissingValuesFrom: { params: true },
    url: `${baseApiUrl}/api/external/me`,
  },
  connectionLabel: (z, bundle) => {
    const { email } = bundle.inputData;
    return `User: ${email}`;
  },
  fields: [
    {
      computed: false,
      key: 'api_key',
      required: false,
      label: 'Roxie API Key',
      type: 'password',
      helpText:
        'Your Roxie API Key can be found here https://app.roxie.ai/account',
    },
  ],
  customConfig: {},
};
