{
  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-24.11";

    utils.url = "github:wunderwerkio/nix-utils";
    utils.inputs.nixpkgs.follows = "nixpkgs";

    devenv.url = "git+https://git.drupalcode.org/project/module_devenv.git?ref=1.x";
    devenv.inputs.nixpkgs.follows = "nixpkgs";
  };

  outputs = {
    self,
    nixpkgs,
    utils,
    devenv
  }: utils.lib.systems.eachDefault (system:
    let
      pkgs = import nixpkgs {
        inherit system;
      };
      mkDrupalModuleDevShell  = devenv.lib.${system}.mkDrupalModuleDevShell ;
    in {
      devShells = rec {
        # PHP 8.1 / Drupal 10
        php81_drupal10 = mkDrupalModuleDevShell {
          drupalVersionConstraint = "^10";

          buildInputs = with pkgs; [
            php81
            php81Packages.composer
          ];
        };

        # PHP 8.2 / Drupal 10
        php82_drupal10 = mkDrupalModuleDevShell {
          drupalVersionConstraint = "^10";

          buildInputs = with pkgs; [
            php82
            php82Packages.composer
          ];
        };

        # PHP 8.3 / Drupal 10
        php83_drupal10 = mkDrupalModuleDevShell {
          drupalVersionConstraint = "^10";

          buildInputs = with pkgs; [
            php83
            php83Packages.composer
          ];
        };

        # PHP 8.3 / Drupal 11
        php83_drupal11 = mkDrupalModuleDevShell {
          drupalVersionConstraint = "^11";

          buildInputs = with pkgs; [
            php83
            php83Packages.composer
          ];
        };

        default = php81_drupal10;
      };

      formatter = pkgs.alejandra;
    }
  );
}
