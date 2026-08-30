// Environment-based API configuration
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8001';
const IMAGE_SOURCE = import.meta.env.VITE_IMAGE_SOURCE || 'https://ce.integratedgenetics.com/ce';

const endpoints = {
  phone: `${API_BASE_URL}/phoenix_phone_controller.php`,
  lookup: `${API_BASE_URL}/phoenix_lookup_controller.php`,
  estimate: `${API_BASE_URL}/cost_estimate_controller.php`,
  tradingPartners: `${API_BASE_URL}/trading_partner_controller.php`,
  testsAvailable: `${API_BASE_URL}/tests_controller.php`,
  siteStatus: `${API_BASE_URL}/site_status_controller.php`,
  bannerMessages: `${API_BASE_URL}/banner_messages_controller.php`,
  imageSource: IMAGE_SOURCE
};

/**
 * API utility module
 * All API calls centralized here
 * CRITICAL: Never modify payloads - preserve exact structure for PHP backend
 */

export const api = {
  /**
   * Get available tests
   * Endpoint: tests_controller.php
   */
  getTestsAvailable: async (token = null) => {
    const url = endpoints.testsAvailable;
    const headers = {
      'Accept': 'application/json'
    };
    
    if (token) {
      headers['X-CSRF-Token'] = token;
    }

    try {
      const response = await fetch(url, {
        headers,
        credentials: 'omit'
      });
      return await response.json();
    } catch (error) {
      console.error('getTestsAvailable error:', error);
      throw error;
    }
  },

  /**
   * Get site status
   * Endpoint: site_status_controller.php
   */
  getSiteStatus: async (token = null) => {
    const url = endpoints.siteStatus;
    const headers = {
      'Accept': 'application/json'
    };
    
    if (token) {
      headers['X-CSRF-Token'] = token;
    }

    try {
      const response = await fetch(url, {
        headers,
        credentials: 'omit'
      });
      return await response.json();
    } catch (error) {
      console.error('getSiteStatus error:', error);
      throw error;
    }
  },

  /**
   * Get banner messages
   * Endpoint: banner_messages_controller.php
   */
  getBannerMessages: async (token = null) => {
    const url = endpoints.bannerMessages;
    const headers = {
      'Accept': 'application/json'
    };
    
    if (token) {
      headers['X-CSRF-Token'] = token;
    }

    try {
      const response = await fetch(url, {
        headers,
        credentials: 'omit'
      });
      return await response.json();
    } catch (error) {
      console.error('getBannerMessages error:', error);
      throw error;
    }
  },

  /**
   * Get Phoenix lookup data (categories, etc.)
   * Endpoint: phoenix_lookup_controller.php
   */
  getPhoenixLookup: async (token = null) => {
    const url = endpoints.lookup;
    const headers = {
      'Accept': 'application/json'
    };
    
    if (token) {
      headers['X-CSRF-Token'] = token;
    }

    try {
      const response = await fetch(url, {
        headers,
        credentials: 'omit'
      });
      return await response.json();
    } catch (error) {
      console.error('getPhoenixLookup error:', error);
      throw error;
    }
  },

  /**
   * Get trading partners (insurance providers)
   * Endpoint: trading_partner_controller.php
   */
  getTradingPartners: async (token = null) => {
    const url = endpoints.tradingPartners;
    const headers = {
      'Accept': 'application/json'
    };
    
    if (token) {
      headers['X-CSRF-Token'] = token;
    }

    try {
      const response = await fetch(url, {
        headers,
        credentials: 'omit'
      });
      return await response.json();
    } catch (error) {
      console.error('getTradingPartners error:', error);
      throw error;
    }
  },

  /**
   * Verify phone number
   * Endpoint: phoenix_phone_controller.php
   */
  verifyPhone: async (phoneNumber, token = null) => {
    const url = `${endpoints.phone}?phoneNumber=${phoneNumber}`;
    const headers = {
      'Accept': 'application/json'
    };
    
    if (token) {
      headers['X-CSRF-Token'] = token;
    }

    try {
      const response = await fetch(url, {
        headers,
        credentials: 'omit'
      });
      return await response.json();
    } catch (error) {
      console.error('verifyPhone error:', error);
      throw error;
    }
  },

  /**
   * Get cost estimate
   * Endpoint: cost_estimate_controller.php
   * CRITICAL: Payload must match exact structure - no modifications
   */
  getCostEstimate: async (payload, token = null) => {
    const url = endpoints.estimate;
    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    };
    
    if (token) {
      headers['X-CSRF-Token'] = token;
    }

    try {
      const response = await fetch(url, {
        method: 'POST',
        headers,
        credentials: 'omit',
        body: JSON.stringify(payload) // EXACT payload - no modifications
      });

      const responseText = await response.text();

      if (!responseText) {
        const error = new Error(`Empty response from cost estimate endpoint (${response.status})`);
        error.status = response.status;
        error.responseText = responseText;
        throw error;
      }

      try {
        const parsed = JSON.parse(responseText);

        if (!response.ok) {
          const error = new Error(parsed.error || `Cost estimate request failed (${response.status})`);
          error.status = response.status;
          error.responseBody = parsed;
          throw error;
        }

        return parsed;
      } catch (parseError) {
        if (parseError instanceof SyntaxError) {
          const error = new Error(`Invalid JSON from cost estimate endpoint (${response.status})`);
          error.status = response.status;
          error.responseText = responseText;
          throw error;
        }

        throw parseError;
      }
    } catch (error) {
      console.error('getCostEstimate error:', error);
      throw error;
    }
  }
};

// Export endpoints config for components that need direct access
export const config = {
  API_BASE_URL,
  IMAGE_SOURCE,
  APP_ENV: import.meta.env.VITE_APP_ENV || 'local',
  isDevelopment: import.meta.env.DEV,
  isProduction: import.meta.env.PROD
};

export default api;
