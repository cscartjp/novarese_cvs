---
allowed_tools: GitHubMCP(get_issue:*)
description: '指定された GitHub Issue の URL を GitHub MCP の get_issue プロンプトを使って取得し、要件を要約して実装ステップを提案します。'
---

# GitHub Issue の実装プランニング

! echo "Fetching issue content via GitHub MCP from: $ARGUMENTS"
! GitHubMCP.get_issue "$ARGUMENTS" > /docs/issue-{ISSUE ID}.md

@/docs/issue-{ISSUE ID}.md

あなたはベテランのソフトウェアエンジニアです。  
以下の GitHub Issue の内容を読み、

1. 要求事項の要約
2. 実装に必要な主要コンポーネントと技術スタックの提案
3. ステップバイステップの実装手順

を具体的に示してください。
