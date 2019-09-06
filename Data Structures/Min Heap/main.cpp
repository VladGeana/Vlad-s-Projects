#include <iostream>
#include<fstream>
typedef int Heap[200001];
Heap h;
using namespace std;
ifstream f("heapuri.in");
ofstream g("heapuri.out");
int l,p,k,x,n=0,ord,v[200001],t[200001];
inline int father(int nod)
{
    return nod>>1;
}
inline int left_son(int nod)
{
    return nod<<1;
}
inline int right_son(int nod)
{
    return (nod<<1)+1;
}
inline void down(int k)
{
    if(k<n)
    {
    if( (h[k]>h[left_son(k)] && left_son(k)<=n) || (right_son(k)<=n &&  h[k]>right_son(k)))
    {
            if(h[left_son(k)]<h[right_son(k)] && right_son(k)<=n)
            {
                swap(h[k],h[left_son(k)]);
                swap(v[t[k]],v[t[left_son(k)]]);
                swap(t[k],t[left_son(k)]);
                down(left_son(k));
            }
            else if(right_son(k)<=n)
            {
                swap(h[k],h[right_son(k)]);
                swap(v[t[k]],v[t[right_son(k)]]);
                swap(t[k],t[right_son(k)]);
                down(right_son(k));
            }
    }

    }
}
inline void up(int k)
{
    if(k>1)
    {
        if(h[father(k)]>h[k])
        {
            swap(h[father(k)],h[k]);
            swap(v[t[father(k)]],v[t[k]]);
            swap(t[father(k)],t[k]);
            up(father(k));
        }
    }
}
inline void cut(int k)
{
    swap(h[k],h[n]);
    swap(v[t[k]],v[t[n]]);
    swap(t[k],t[n]);
    n--;
    if(h[k]<h[father(k)])
        up(k);
    else
        down(k);
}
int main()
{
    f>>l;
    for(;l;l--)
    {
        f>>x;
        if(x==1)
        {
            f>>x;
            h[++n]=x;
            v[++ord]=n;
            t[n]=ord;
            up(n);
        }
        else if(x==2)
        {
            f>>x;
            cut(v[x]);
        }
        else if(n>1) g<<min(h[1],h[2])<<'\n';
        else g<<h[1]<<'\n';
    }
}
