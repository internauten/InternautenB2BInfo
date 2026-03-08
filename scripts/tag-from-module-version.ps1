param(
  [Alias('n')]
  [switch]$DryRun,

  [Alias('h')]
  [switch]$Help
)

$ErrorActionPreference = 'Stop'

if ($Help) {
  Write-Output "Usage: ./scripts/tag-from-module-version.ps1 [-DryRun]"
  exit 0
}

$moduleFile = Join-Path $PSScriptRoot "..\internautenb2binfo\internautenb2binfo.php"
$moduleFile = [System.IO.Path]::GetFullPath($moduleFile)

if (-not (Test-Path -LiteralPath $moduleFile)) {
  Write-Error "Module file not found: $moduleFile"
  exit 1
}

$content = Get-Content -LiteralPath $moduleFile -Raw
$match = [regex]::Match($content, "\$this->version\s*=\s*'([^']+)'")

if (-not $match.Success) {
  Write-Error "Could not extract module version from $moduleFile"
  exit 1
}

$version = $match.Groups[1].Value
$tag = "v$version"

# Force git to resolve paths relative to the repository root.
Push-Location (Join-Path $PSScriptRoot "..")
try {
  & git rev-parse --verify --quiet $tag | Out-Null
  if ($LASTEXITCODE -eq 0) {
    Write-Error "Tag already exists locally: $tag"
    exit 1
  }

  $remoteTag = & git ls-remote --tags origin "refs/tags/$tag"
  if ($remoteTag) {
    Write-Error "Tag already exists on origin: $tag"
    exit 1
  }

  if ($DryRun) {
    Write-Output "Dry-run: would create and push tag $tag"
    Write-Output "Dry-run: git tag -a $tag -m \"Release $tag\""
    Write-Output "Dry-run: git push origin $tag"
    exit 0
  }

  & git tag -a $tag -m "Release $tag"
  if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

  & git push origin $tag
  if ($LASTEXITCODE -ne 0) { exit $LASTEXITCODE }

  Write-Output "Created and pushed tag: $tag"
}
finally {
  Pop-Location
}
