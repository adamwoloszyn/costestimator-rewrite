import { config } from '../utils/api';
import './EnvIndicator.scss';

/**
 * Environment Indicator Component
 * Displays current environment in development mode
 * Hidden in production builds
 */
function EnvIndicator() {
  // Only show in development
  if (!config.isDevelopment) {
    return null;
  }

  return (
    <div className="env-indicator">
      <span className="env-badge" data-env={config.APP_ENV}>
        {config.APP_ENV.toUpperCase()}
      </span>
      <span className="env-details">
        API: {config.API_BASE_URL}
      </span>
    </div>
  );
}

export default EnvIndicator;
